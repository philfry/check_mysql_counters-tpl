# check_mysql_counters -- template

This is a rewrite of the template of [Jason's check_mysql_counters](https://github.com/jasonholtzapple/check_mysql_counters).

Changes/improvements:
* cleanup for a better readability
* use named indices instead of numbered indices, b/c the numbers happen to be wrong
* check existence of the data sources before adding them to the graph
