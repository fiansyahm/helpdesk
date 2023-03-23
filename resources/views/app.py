import base64
from flask import Flask, render_template, redirect, url_for,request
from flask import make_response
from flask_cors import CORS


import io
import random
from flask import Response
from matplotlib.backends.backend_agg import FigureCanvasAgg as FigureCanvas
from matplotlib.figure import Figure
import matplotlib
from tabulate import tabulate
matplotlib.use('Agg')
from matplotlib.pylab import *
from flask_mysqldb import MySQL


app = Flask(__name__)
CORS(app)
from flask_mysqldb import MySQL
app.config['MYSQL_HOST'] = 'localhost' # ganti dengan host dari MySQL Anda
app.config['MYSQL_USER'] = 'root' # ganti dengan username MySQL Anda
app.config['MYSQL_PASSWORD'] = '' # ganti dengan password MySQL Anda
app.config['MYSQL_DB'] = 'project_TA' # ganti dengan nama database yang ingin Anda gunakan
app.config['MYSQL_CURSORCLASS'] = 'DictCursor'
mysql = MySQL(app)


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


@app.route('/create_graphimage_table')
def create_graphimage_table():
    cur = mysql.connection.cursor()
    cur.execute("CREATE TABLE graphimage (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, nama_project VARCHAR(255) NOT NULL, base64code LONGTEXT NOT NULL)")
    mysql.connection.commit()
    cur.close()
    return "Tabel berhasil dibuat!"

def query(nama_project,base64code):
    cur = mysql.connection.cursor()
    cur.execute("INSERT INTO graphimage (nama_project, base64code) VALUES (%s, %s)", (nama_project, base64code))
    mysql.connection.commit()
    cur.close()
    return "Data berhasil disimpan!"



def getData(data=None):
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
            row.append([])
        pairs.append(row)
    authors = sorted(set(authors))
    return pairs,authors

def author_matrixs(authors):
    author_matrix=[]
    for author_x in authors:
        for author_y in authors:
            row=[]
            row.append(author_x)
            row.append(author_y)
            author_matrix.append(row)
    return author_matrix

def getTable2Data(pairs,search_matrix):
    author_matrixs=[]
    for i in search_matrix:
        author_matrixs.append([i[0],i[1],0])

    print("getTable2Data")
    for i in pairs:
        penulisList=i[1]
        authorList=i[2]
        for author in authorList:
            # try:
                row_author=[]
                for row in pairs:
                    if author == row[0]:
                        for author2 in row[1]:
                            row_author.append(author2)
                        # skip karena sudah ketemu
                        break;

                for author in penulisList:
                    for row in row_author:
                        if author != row:
                            index=search_matrix.index([author, row])
                            author_matrixs[index][2]+=1
                print("\n")
            # except:
            #     pass

    return author_matrixs

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
            if y[1] == x:
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

import numpy as np
import matplotlib.pyplot as plt
import networkx as nx

def makeTermGraph(table, authors,author_matrixs,author_rank,outer_author,ranking):
    rank_outer_author=author_rank[len(author_rank)-1]
    G = nx.Graph()
    labels = {}
    my_node_sizes=[]
    my_node_colors=[]
    my_node_label_color=[]

    author_ranking = []
    count=-1
    for author in authors:
        count+=1
        author_ranking.append((author, author_rank[count]))

    sorted_authors = sorted(author_ranking, key=lambda x: x[1], reverse=True)

    # get the top 20 author names
    top_authors = [x[0] for x in sorted_authors[:ranking]]
    
    count=-1
    # Add nodes to the graph
    for author, size in zip(authors, author_rank):
        count+=1
        G.add_node(author)
            
        if size > rank_outer_author:
            # jika iya nilainya *300
            my_node_sizes.append(size *300)
            if author in top_authors:
                my_node_colors.append('purple')
            else:
                my_node_colors.append('blue')
            labels[author]=author
        else:
            # jika tidak dirujuk nilainya 10
            if outer_author == True:
                my_node_sizes.append(8)
                my_node_label_color.append(8)
                labels[author]=author
            else:
                my_node_sizes.append(0)
                labels[author]=""

            my_node_colors.append('red')
    

    G = nx.Graph()
    # Add nodes to the graph
    for author in authors:
        G.add_node(author)

    for author_matrix in author_matrixs:
        if author_matrix[2] > 0:
            # print("value:"+str(author_matrix[2]))
            G.add_edge(author_matrix[0], author_matrix[1], weight=author_matrix[2])
            index=authors.index(author_matrix[1])
            if my_node_sizes[index] == 8 or my_node_sizes[index] == 0:
                # node yang merujuk tapi tidak dirujuk ubah size=100
                my_node_sizes[index] = 100
                labels[authors[index]]=authors[index]
    # Draw the graph
    # fig, ax = plt.subplots(figsize=(15,12)) # increase plot size to 10x8 inches
    fig, ax = plt.subplots(figsize=(90,72)) # increase plot size to 10x8 inches
    pos = nx.spring_layout(G, seed=7, k=0.4) # decrease k parameter to increase spacing between nodes
    nx.draw_networkx_nodes(G, pos, node_size=my_node_sizes, alpha=0.7, node_color=my_node_colors) # increase node size to 200
    nx.draw_networkx_edges(G, pos, edgelist=G.edges(), width=1, alpha=0.5, edge_color="b")    
    nx.draw_networkx_labels(G, pos,labels,font_size=8, font_family="sans-serif",font_color="black")
    
    edge_labels = nx.get_edge_attributes(G,name='weight')
    edge_labels={(u, v): weight_matrix for u, v, weight_matrix in G.edges(data='weight')}
    nx.draw_networkx_edge_labels(G, pos, edge_labels, font_size=5)
    buf = io.BytesIO()
    plt.savefig(buf, format='png')

    output=buf
    output.seek(0)
    my_base64_jpgData = base64.b64encode(output.read())
    # query("project23maret",my_base64_jpgData)

    return buf


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

def rank(pretable3,lenauthor,name):
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

    if name=="graph":
        return table4,rowbaru
    elif name=="rank":
        return table4,rank



@app.route('/data/<name>', methods=['GET', 'POST'])

def data(name):
    if request.method == 'POST' or request.method == 'GET':
        if request.method == 'POST':
            table=getData(request.get_json()["data"]);
        elif request.method == 'GET':
            table=getData();
        
        print("Tabel 1")
        title=[ 'Article-ID', 'Terms in Title and Keywords', 'Terms Found in Abstracts','Publication Year','Authors','References']
        print(title)
        print(tabulate(table))
        
        
    # pair ArticleId,Author,& References & author
        pairs,authors=getArticleIdAuthorReferencesAndAuthor(table)
        # for i in pairs:
        #     print(i)
        #     print("\n")
        # for y in authors:
        #     print(y)
        #     print("\n")
        
    # pasangan yang memungkinkan antara 2 penulis
        author_matrix=author_matrixs(authors) 


    # ambil data untuk tabel 2(step 1)
        author_matrix_and_relation=getTable2Data(pairs,author_matrix)
        # for x in author_matrix_and_relation:
        #     print(x)
        # return author_matrix_and_relation

    # errornyadisini
        table2,raw_table2=makeTable2(author_matrix_and_relation,authors)
        # add total coloum & row in table 2
        raw_table2WithRowCol=addTable2TotalRowAndColoumn(raw_table2,authors)
        # makeNewAdjMatrix
        newAdjMatrixs=makeNewAdjMatrix(raw_table2WithRowCol,len(authors))
        # rank author
        table,author_rank=rank(newAdjMatrixs,len(authors),name)

        try:
            outer_author= request.get_json()["outer"]
            top_author_rank= request.get_json()["author-rank"]
        except:
            outer_author= True
            top_author_rank= 10

        if name == "graph":
        # Make Term Graph
            output=makeTermGraph(table2,authors,author_matrix_and_relation,author_rank,outer_author,top_author_rank)
            output.seek(0)
            import base64
            my_base64_jpgData = base64.b64encode(output.read())
            if request.method == 'GET':
                return Response(output.getvalue(), mimetype='image/png') 
            else:
                return my_base64_jpgData
        elif name == "rank":
            return [authors,[table,author_rank]]

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