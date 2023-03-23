def makeTermGraph(table, authors,author_matrixs,author_rank,outer_author,ranking):
    rank_outer_author=author_rank[len(author_rank)-1]
    author_matrix = np.array(author_matrix)
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
            print("value:"+str(author_matrix[2]))
            G.add_edge(author_matrix[0], author_matrix[1], weight=author_matrix[2])
            index=author.index(author_matrix[1])
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
    return buf

output=makeTermGraph(table2,authors,author_matrix_and_relation,output_rank,outer_author,top_author_rank)
