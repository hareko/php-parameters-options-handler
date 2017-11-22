# PHP parameters and Options handler #

There are two typical tasks, among the others, when you initialise the PHP script:

- accept the request or CLI parameters;
- assign the settings' values basing on the default options and the parameter values.

The *ParmOpts* class supplies the methods simplifying these tasks.

## How it works ##

The data input is detected during the class instantiation and the request or CLI parameters are saved.
The request parameters are accepted from different sources by overwriting the values of the same key according to given priority.

The default options are updated with the input parameter values by adjusting the data types taken from the defaults.
The allowance is checked before adjusting the type or replacing the default value with the empty one.
The saved parameters and options can be accessed as arrays or objects.

## The usage ##

### Instantiation ###

**$obj = new ParmOpts( [ $pty ] );**

**$pty** - the merging priority of the request parameters (higher to a lower, default by *'JPG'*):

- *J* - json data
- *P* - post data
- *G* - get data

The parameters are saved by the constructor.


### Methods ###

**$obj->Opts( $opt [, $rqt = null ] );**

Save the options, updating the defaults with the parameter values.

- **$opt** - default options associative array (*'name' => 'default value'*)
- **$rqt** - request parameters (default by input parameters)

**$obj->Get( [ $prp = 'rqt' [, $flg = false ] ] );**

Read the saved data.

- **$prp** - property:
    - *'rqt'* - request or CLI parameter values
    - *'opt'* - option values
    - *'jsn'* - json request flag (*true/false*)
- **$flg** - data format (ignored for *'jsn'*): 
    - *true* - dual, can be accessed as array or object
    - *false* - array

## The package ##

Upload the files to any web directory and run the *example.php*.
The following files are included:

- *ParmOpts.php* - the class to handle parameters and options
- *example.php* - usage sample
- *readme.md*

## ChangeLog ##

01 Apr 2016

- *ParmOpts.php*
    - *jsn* property - json content (*array*) or missing

22 Nov 2017

- *ParmOpts.php*
    - *mds* property - several modes (*array*):
      - *http2* - HTTP/2 protocol (*true*)
      - *https* - secured connection (*true*)
      - *xhr* - AJAX reqguest (*true*)
      - *rqm* - request method: CLI - command line, else GET, POST,...

Please [contact] on any product-related questions.

[contact]: mailto://vallo@vregistry.com
