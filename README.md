trello-tfs-cli
==============

Small bridge between TFS and Trello for creating sprint boards based on export from TFS web app

* This is based on the data you get when selecting multiple items in the web app and clicking "Copy"
* You have to have certain columns in the export (ID, Work Type Item, Title, Story Points)
* You will need to generate an application key/secret pair and generate a config file
* It runs on php, you will need at least php 5.4 and composer
* It assumes you have Scrum for Trello, as it adds story points to the name
* You can create a board and list existing boards (with all the cards on a board)


It doesn't
* Add team members to the board, you will have to remember, although I may implement this
* Add all the columns you want. Again, may add this, and have a config array of columns to create
