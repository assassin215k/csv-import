## Install dockers

1. Open terminal and go to project directory
2. run ```chmod +x .docker/docker-install.sh``` to make file executable
3. and run ```sudo .docker/docker-install.sh``` to install docker, build containers and link commands to containers

### Attention!
Old docker and containers will be removed 
After build new will be copied commands to ```/usr/bin``` and ```/usr/local/bin``` and it can erase your current commands  
Required reboot after first installation

#### Check installation
check php by run ```php -v``` and composer by run ```composer --version```

#### Run symfony
1. make database using ./info/make_database.sql
2. Run migrations by ```php bin/console doctrine:migrations:migrate```
3. in console run command php bin/console app:csv-import ./.info/stock.csv

### Code standard
To use Symfony standard import ```.editconfig``` in ```File -> Settings -> Editor -> Code Style -> PHP```

### Run import command
To run import use ```php bin/console app:csv-import ./.info/stock.csv``` command
where ```./.info/stock.csv``` is the path to your csv file

### Tests coverage
To see report open ```./xHTML/index.html``` after tests run

============

## Candidate Development Test

### The problem
In order to add some new and exciting products to the site, we need to process a CSV file
from a supplier.

This file contains product information which we need to extract and insert into a database
table.

In addition, we need to apply some simple business rules to the data we import. A table
already exists to receive this information, but the table needs some tweaks in order to work
correctly with this file.

### The Solution
You need to create a mechanism which will read the CSV file, parse the contents and then insert
the data into a MySQL database table.

The import process will be run from the command line and when it completes it needs to
report how many items were processed, how many were successful, and how many were
skipped. See the import rules below.

### Objectives
Your solution **must be OO**, based on Symfony framework (version 4 or higher) and use MySQL. Code should be clearly laid out, well commented and covered by unit tests.

Any SQL used to alter the table should be included as migration scripts with the submission.

Using a command line argument the script can be run in 'test' mode. This will perform
everything the normal import does, but not insert the data into the database.

The supplier provides a stock level and price which we currently do not store. Using
suitable data types, add two columns to the table to capture this information.

Result should be presented as a pull request that contains only code related to the problem (excluding initial symfony project commit).

### Import Rules
Any stock item which costs less that $5 and has less than 10 stock will not be imported.

Any stock items which cost over $1000 will not be imported.

Any stock item marked as discontinued will be imported, but will have the discontinued
date set as the current date.

Any items which fail to be inserted correctly need to be listed in a report at the end of the
import process.

### Hints
Look for existing solutions/libraries that could help organize the import code and all the rules in a nice OO program.

### Additional Considerations
Because the data is from an external source, it may present certain issues.

These include:
1. Whether the data is correctly formatted for CSV
2. Whether the data is correctly formatted for use with a database
3. Potential data encoding issues or line termination problems
4. Manual interference with the file which may invalidate some entries

Either address these concerns in the code or indicate in your response how you would
tackle these issues if you had more time to develop your script.
