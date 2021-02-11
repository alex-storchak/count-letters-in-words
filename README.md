# count-letters-in-words
Script calculates minimum amount of each letter in an alphabet for constructing any word in the language.

Files `/data/dict/example/russian.txt` and `/data/dict/example/english.txt` contain kind of summary of all words in
Russian and English languages respectively. Both of them have been found
somewhere in the open source.

Prepare data:
 - place a dictionary file to the directory `/data/dict`

To run script use (specify arguments, both of them required)
 
`php run.php [file name with extension for dictionary from /data/dict] [string with an alphabet of the chosen language]`  

Optionally it's possible to specify argument with a maximum amount
of example words in the result array (default is 100)

`php run.php english.txt ABCDEFGHIJKLMNOPQRSTUVWXYZ 10`

Result file will be created at `/data/result/result_[file name with extension for dictionary from /data/dict]`.