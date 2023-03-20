import sys
import json

from appupgrade import dataku

# Get the filename from the command line arguments
filename = sys.argv[2]

# Load the JSON data from the file
with open(filename, "r") as f:
    data = json.load(f)

# Process the data as needed
# Here's an example that prints the name and IDs of all items
for item in data["data"]:
    # print(f"{item[0]}: {item[4]}")
    pass
print(dataku("graph"))
# Exit with a success status code
sys.exit(0)
