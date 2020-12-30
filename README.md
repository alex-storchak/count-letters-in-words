# count-letters-in-words
Script calculates minimum amount of each letter in an alphabet for constructing any word in the language.

Files `/data/dict/example/russian.txt` and `/data/dict/example/english.txt` contain kind of summary of all words in
Russian and English languages respectively. Both of them have been found
somewhere in the open source.

Prepare data:
 - place a dictionary file to the directory `/data/dict`
 - add correct method call into run.php as shown in the example there 

To run script use
 
`php run.php`
 
Optionally it's possible to specify argument with a maximum amount
of example words in the result array (default is 100)

`php run.php 10`
