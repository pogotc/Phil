# Phil

![License](https://poser.pugx.org/behat/behat/license)
[![Build Status](https://travis-ci.org/pogotc/Phil.svg?branch=master)](https://travis-ci.org/pogotc/Phil)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pogotc/Phil/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pogotc/Phil/?branch=master)

PHp Interpreted Lisp.

Phil is a LISP dialect loosely based on the syntax used by Clojure.  

## Usage

Phil can either be invoked by running `bin/phil` or using `./phil.phar` (coming soon). Running this will open the REPL
where you can run simple commands. The binary also accepts a file path to run code contained in a script file.

## Example Code

As with all LISP dialects the pattern is `(functionname args)`, so to run a simple Hello World app you run:

    (println "Hello, World")
    
or to add a list of numbers together:

    (+ 1 2 3 4) ; returns 10

Functions can be declared using the `defn` keyword found in Clojure:

    (defn sayHello (name) (println (+ "Hello, " name)))
    
    (sayHello "Bob") ; returns "Hello, Bob"
    
Recursive functions and conditionals are also supported allow for code such as:

    (defn length (xs)
    	(if
		    (= 0 (count xs))
		    0
		    (+ 1 (length (rest xs)))
	    )
    )
    
    (length '(1 2 3 4 5)) ; returns 5


