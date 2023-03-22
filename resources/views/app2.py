from flask import Flask, render_template, redirect, url_for,request
from flask import make_response
from flask_cors import CORS


import io
import random
from flask import Response
from matplotlib.backends.backend_agg import FigureCanvasAgg as FigureCanvas
from matplotlib.figure import Figure
import matplotlib
import pandas as pd
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
    pairs = []
    authors = []
    for row in table:
        article_id = row[0]
        author_list = row[4]
        reference_list = row[5] if len(row) == 6 else []
        pairs.append([article_id, author_list, reference_list])
        authors.extend(author_list)
    unique_authors = sorted(set(authors))
    authors = unique_authors
    return pairs,authors

def author_matrixs(authors):
    print("Semua Kemungkinan Relasi Antar Penulis")
    from itertools import product

    # Get all possible author pairs (including self-referential pairs)
    author_pairs = list(product(authors, authors))

    # Display all possible author pairs (including self-referential pairs)
    author_matrix=[]
    for pair in author_pairs:
        row=[pair[0],pair[1],0]
        author_matrix.append(row)
    return author_matrix

def getTable2Data(pairs,author_matrix):
    for pair in pairs:
        authors_from_article=pair[1]
        references=pair[2]
        for reference in references:
            for author_2 in pairs:
                if author_2[0] == reference:
                    for author_2_detail in author_2[1]:
                        for author_detail in authors_from_article:
                            for i in range(len(author_matrix)):
                                if author_matrix[i][0] == author_detail and author_matrix[i][1] == author_2_detail and author_2_detail!=author_detail:
                                    author_matrix[i][2] += 1
    return author_matrix
        
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
import networkx as nx
import matplotlib.pyplot as plt
import io

def makeTermGraph(table, authors, author_matrix):
    author_matrix = np.array(author_matrix)
    G = nx.Graph()

    # Add nodes to the graph
    for author in authors:
        G.add_node(author)

    rows, cols = np.where(table > 0)
    edges = zip(rows.tolist(), cols.tolist())

    # Add edges to the graph with weights
    for x, y in edges:
        row_index = np.where((author_matrix[:,0] == authors[y]) & (author_matrix[:,1] == authors[x]))
        value = int(author_matrix[row_index, 2][0])
        G.add_edge(authors[x], authors[y], weight=value)

    # Draw the graph
    # fig, ax = plt.subplots(figsize=(15,12)) # increase plot size to 10x8 inches
    fig, ax = plt.subplots(figsize=(90,72)) # increase plot size to 10x8 inches
    pos = nx.spring_layout(G, seed=7, k=0.4) # decrease k parameter to increase spacing between nodes
    nx.draw_networkx_nodes(G, pos, node_size=200, alpha=0.7) # increase node size to 200
    nx.draw_networkx_edges(G, pos, edgelist=G.edges(), width=1, alpha=0.5, edge_color="b")
    nx.draw_networkx_labels(G, pos, font_size=8, font_family="sans-serif")
    edge_labels = nx.get_edge_attributes(G, "weight")
    nx.draw_networkx_edge_labels(G, pos, edge_labels, font_size=5)
    buf = io.BytesIO()
    plt.savefig(buf, format='png')
    return buf


def addTable2TotalRowAndColoumn(pretable2,authors):
    # Initialize list for row and column totals
    row_totals = [0] * len(pretable2)
    col_totals = [0] * len(pretable2[0])

    # Calculate row and column totals
    for i, row in enumerate(pretable2):
        for j, val in enumerate(row):
            row_totals[i] += val
            col_totals[j] += val

    # Add row and column totals to pretable2
    for i, row in enumerate(pretable2):
        row.append(row_totals[i])
    col_totals.append(sum(col_totals))
    pretable2.append(col_totals)
    return pretable2

def makeNewAdjMatrix(pretable3,lenauthor):
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

    # Set damping factor
    d = 0.850466963

    # Initialize row vector
    row = [1 / lenauthor] * lenauthor

    # Initialize table4 with row vector
    table4 = [row]

    # Iterate to calculate PageRank
    for i in range(10000):
        row_new = []
        for j in range(lenauthor):
            val = (1 - d) + d * np.matmul(pretable3[j][:lenauthor], row[:lenauthor])
            row_new.append(val)
        table4.append(row_new)
        diff = np.abs(np.array(row) - np.array(row_new)).max()
        if diff < 0.001:
            break
        row = row_new

    # Calculate ranking from PageRank
    rank = [sorted(row, reverse=True).index(x) for x in row]
    rank = [x + 1 for x in rank]

    # Add rank vector to table4
    table4.append(rank)

    # Create pandas DataFrame for table5
    table5 = pd.DataFrame(table4)

    # Transpose table5 for better display
    table5 = table5.T

    print("tabel 3: Ranking")
    print(table5)

    return table4,rank



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
            output=makeTermGraph(table2,authors,author_matrix)
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