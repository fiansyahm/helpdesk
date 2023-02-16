from flask import Flask, render_template, redirect, url_for,request
from flask import make_response
from flask_cors import CORS


import io
import random
from flask import Response
from matplotlib.backends.backend_agg import FigureCanvasAgg as FigureCanvas
from matplotlib.figure import Figure
import matplotlib
matplotlib.use('Agg')
from matplotlib.pylab import *

app = Flask(__name__)
CORS(app)

@app.route("/")
def home():
    return "hi"
@app.route("/index")

@app.route('/login', methods=['GET', 'POST'])
def login():
   message = None
   if request.method == 'POST':
        datafromjs = request.form['mydata']
        # result = "return this"
        # resp = make_response('{"response": '+result+'}')
        # resp.headers['Content-Type'] = "application/json"
        # return resp
        # return render_template('login.html', message='')

        fig = Figure()
        axis = fig.add_subplot(1, 1, 1)
        xs = range(100)
        ys = [random.randint(1, 50) for x in xs]
        axis.plot(xs, ys)
        import urllib.parse
        output = io.BytesIO()
        FigureCanvas(fig).print_png(output)
        return Response(output.getvalue(), mimetype='image/png')
   else:
        fig = Figure()
        axis = fig.add_subplot(1, 1, 1)
        xs = range(100)
        ys = [random.randint(1, 50) for x in xs]
        axis.plot(xs, ys)
        
        output = io.BytesIO()
        FigureCanvas(fig).print_png(output)
        return Response(output.getvalue(), mimetype='image/png')



def getData(data=None):
        from tabulate import tabulate
        title=[ 'Article-ID', 'Terms in Title and Keywords', 'Terms Found in Abstracts','Publication Year','Authors','References']
        print("data masukan:")
        print(data)
        if data==None:
            table = [  
                [ "a1", ['a','b','c'],   ['a','b','c','k','l']    ,'1993',['p1','p2']                                              ]
                , [ "a2", ['c','d','e'],   ['a','c','d','e','m','n'],'1993',['p1','p3']                                              ]
                , [ "a3", ['f','g','h'],   ['c','d','f','g','h','o'],'1993',['p2','p4','p5']                                         ]
                , [ "a4", ['i','j'],       ['c','d','p','q']        ,'1994',['p3','p6']      ,['a1','a2']                            ]
                , [ "a5", ['dj','dk'],     ['a','dj','dk','m','r']  ,'1994',['p1','p7']      ,['a1','a2','a3']                       ]
                , [ "a6", ['d','ac','ad'], ['d','ac','ad','s','t']  ,'1994',['p8','p9']      ,['a1','a3']                            ]
                ]
        else:
            table=data
        print("tabel 1")
        print(title)
        print(tabulate(table))
        return(table)

def getArticleIdAuthorReferencesAndAuthor(table):
    pairs=[]
    authors=[]
    for i in table:
        row=[]
        row.append(i[0])
        row.append(i[4])
        for penulis in i[4]:
            authors.append(penulis)
        try:
            row.append(i[5])
        except:
            print("")
        pairs.append(row)
    return pairs,authors

def author_matrixs(authors):
    print("Semua Kemungkinan Relasi Antar Penulis")
    author_matrix=[]
    for author_x in authors:
        for author_y in authors:
            row=[]
            row.append(author_x)
            row.append(author_y)
            author_matrix.append(row)
    for x in author_matrix:
        print(x)
    return author_matrix
def getTable2Data(pairs,author_matrix):
    for i in pairs:
        try:
            penulisList=i[1]
            authorList=i[2]
            authorListExpand=[]
            print(penulisList+authorList)
            for author in authorList:
                row_author = [x[1] for i, x in enumerate(pairs) if author in x][0]
                # row_author=pairs[index_2d(pairs, author)][1]
                print(row_author)
                for every_author in row_author:
                    print(every_author)
                    authorListExpand.append(every_author)
            print("\n")
            for authorListExpandChild in authorListExpand:
                print("child:")
                print(authorListExpandChild)
            print("\n")

            for penulis in penulisList:
                for child in authorListExpand:
                    if penulis == child:
                        continue
                    print("penulis:",penulis,child)
                    try:
                        index=author_matrix.index([penulis, child])
                        author_matrix[index].append(authorListExpand.count(child))
                        print("nilai:",author_matrix[index][2])
                    except:
                        continue
        except:
            continue
    return author_matrix

def index_2d(myList, v):
    for i, x in enumerate(myList):
        if v in x:
            return i #, x.index(v)
        
def makeTable2(author_matrix,authors):
    import pandas as pd
    pretable2=[]
    for x in authors:
        authortmp=[]
        for y in author_matrix:
            if y[1] in x:
                try:
                    authortmp.append(y[2])
                except:
                    authortmp.append(0)
        pretable2.append(authortmp)
    # print(pretable2)
    table2=pd.DataFrame(pretable2, columns=authors,index=authors)
    print("tabel 2")
    print(table2)
    return table2,pretable2

def makeTermGraph(table2):
    import numpy as np
    import matplotlib.pyplot as plt
    import networkx as nx
    # rows, cols = np.where(table2 >= 1)
    # edges = zip(rows.tolist(), cols.tolist())
    # gr = nx.Graph()
    # gr.add_edges_from(edges)
    # nx.draw(gr, node_size=500,with_labels=True)
    # plt.show()


    G = nx.Graph()
    rows1, cols1 = np.where(table2 == 1)
    edges1 = zip(rows1.tolist(), cols1.tolist())
    for x,y in edges1:
        G.add_edge(x, y, weight=1)
    rows2, cols2 = np.where(table2 == 2)
    edges2 = zip(rows2.tolist(), cols2.tolist())
    for x,y in edges2:
        G.add_edge(x, y, weight=2)

    elarge = [(u, v) for (u, v, d) in G.edges(data=True) if d["weight"] ==1 ]
    esmall = [(u, v) for (u, v, d) in G.edges(data=True) if d["weight"] == 2]

    pos = nx.spring_layout(G, seed=7)  # positions for all nodes - seed for reproducibility

    # nodes
    nx.draw_networkx_nodes(G, pos, node_size=700)

    # edges
    nx.draw_networkx_edges(G, pos, edgelist=elarge, width=6)
    nx.draw_networkx_edges(
        G, pos, edgelist=esmall, width=6, alpha=0.5, edge_color="b", style="dashed"
    )

    # node labels
    nx.draw_networkx_labels(G, pos, font_size=20, font_family="sans-serif")
    # edge weight labels
    edge_labels = nx.get_edge_attributes(G, "weight")
    nx.draw_networkx_edge_labels(G, pos, edge_labels)

    # ax = plt.gca()
    # ax.margins(0.08)
    # plt.axis("off")
    # plt.tight_layout()
    # print("term Graph")
    # plt.show()
    # return plt

    buf = io.BytesIO()
    plt.savefig(buf, format='png')
    return buf

    # G = nx.from_numpy_matrix(np.matrix(table2), create_using=nx.DiGraph)
    # layout = nx.spring_layout(G)
    # nx.draw(G, layout)
    # nx.draw_networkx_edge_labels(G, pos=layout)
    # plt.show()
def addTable2TotalRowAndColoumn(pretable2,authors):
    import pandas as pd
    sumrow=[]
    sumcol=[]
    lenauthor=len(authors)
    for x in range(lenauthor):
        nilai=0
        for y in range(lenauthor):
            nilai=nilai+pretable2[x][y]
        sumrow.append(nilai)
    print("p1p9")
    print(sumrow)

    sumcol=[]
    for x in range(lenauthor):
        nilai=0
        for y in range(lenauthor):
            nilai=nilai+pretable2[y][x]
        sumcol.append(nilai)
    sumcol.append(0)
    print("p9p1")
    print(sumcol)
    for x in range(lenauthor):
        pretable2[x].append(sumrow[x])
    pretable2.append(sumcol)
    print(pretable2)
    print("tabel 3: Add Total Row & Col")
    table2=pd.DataFrame(pretable2)
    print(table2)
    return pretable2

def makeNewAdjMatrix(pretable3,lenauthor):
    import pandas as pd
    for x in range(lenauthor):
        for y in range(lenauthor):
            if pretable3[lenauthor][y] == 0:
                # print("nilaiku="+str(pretable3[x][y]))
                pretable3[x][y]=1/lenauthor
            else:
                # print("nilaiku="+str(pretable3[x][y]))
                pretable3[x][y]=pretable3[x][y]/pretable3[lenauthor][y]
    table3=pd.DataFrame(pretable3)
    print("tabel 3:new adj Matrix")
    print(table3)
    return pretable3
def rank(pretable3,lenauthor):
    import numpy as np
    import pandas as pd
    d=0.850466963
    table4=[]
    row=[]
    for x in range(lenauthor):
        row.append(1/lenauthor)
    table4.append(row)
    for y in range(100):
        rowbaru=[]
        for x in range(lenauthor): 
            nilai=(1-d)+d*np.matmul(pretable3[x][0:lenauthor],row[0:lenauthor])
            rowbaru.append(nilai)
        table4.append(rowbaru)
        selisih=abs(np.array(row)-np.array(rowbaru))
        ns=max(selisih)
        if ns < 0.001:break;
        #print(ns)
        row=rowbaru
    rank=[sorted(row,reverse=True).index(x) for x in row]
    rank = [x + 1 for x in rank]
    table4.append(rank)   
    table5=pd.DataFrame(table4)
    print("tabel 3: Ranking")
    print(table5.T)
    return table4



@app.route('/data/<name>', methods=['GET', 'POST'])

def data(name):
    if request.method == 'POST' or request.method == 'GET':
    # tabel 1
        # requestjson=request.get_json()
        # if request.method == 'POST':
        #     table=getData(1,requestjson["data"]);
        # if request.method == 'GET':
        #     table=getData(0,None);
        if request.method == 'POST':
            table=getData(request.get_json()["data"]);
        elif request.method == 'GET':
            table=getData();
        
    # get pair ArticleId,Author,& References
    # get authors
        pairs,authors=getArticleIdAuthorReferencesAndAuthor(table)
        for i in pairs:
            print(i)
            print("\n")
        authors=sorted(set(authors))
        for y in authors:
            print(y)
            print("\n")
    # posibly author can be arranged
        author_matrix=author_matrixs(authors) 
    # get data to make table 2(step 1)
        author_matrix_and_relation=getTable2Data(pairs,author_matrix)
        # for x in author_matrix_and_relation:
        #     print(x)
    # get data to make table 2(step 2)

    # errornyadisini
        table2,raw_table2=makeTable2(author_matrix_and_relation,authors)
        if name == "graph":
        # Make Term Graph
            output=makeTermGraph(table2)
            output.seek(0)
            import base64
            my_base64_jpgData = base64.b64encode(output.read())
            if request.method == 'GET':
                return Response(output.getvalue(), mimetype='image/png') 
            else:
                return my_base64_jpgData
        elif name == "rank":
        # add total coloum & row in table 2
            raw_table2WithRowCol=addTable2TotalRowAndColoumn(raw_table2,authors)
        # makeNewAdjMatrix
            newAdjMatrixs=makeNewAdjMatrix(raw_table2WithRowCol,len(authors))
        # rank author
            return [authors,rank(newAdjMatrixs,len(authors))]  

if __name__ == "__main__":
    app.run(debug = True)

# fig = Figure()
#         axis = fig.add_subplot(1, 1, 1)
#         xs = range(100)
#         ys = [random.randint(1, 50) for x in xs]
#         axis.plot(xs, ys)
#         output = io.BytesIO()

#         from io import BytesIO
#         from PIL import Image, ImageDraw
#         image = Image.new("RGB", (300, 50))
#         draw = ImageDraw.Draw(image)
#         draw.text((0, 0), "This text is drawn on image")

#         image.save(output, 'PNG')
#         import base64
#         return base64.b64encode(output.getvalue()).decode()
        
#         FigureCanvas(fig).print_png(output)
#         return Response(output.getvalue(), mimetype='image/png')