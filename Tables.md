# Tables

[Error Codes](Errors.md)

Data is returned as XML by default, or if the extension `xml`, and HTML if the extension is `html`.

## Request

### Format 1 - Tables for a club

**URI:** `http://www.southlacrosse.org.uk/rest/clubs/{club-id}/tables[.xml|.html]`

**club-id** is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#clubs)).

### Format 2 - Tables for a division

**URI:** `http://www.southlacrosse.org.uk/rest/tables/mens/{division}[.xml|.html]`

**division** is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#divisions)).

### Format 3 - All tables

**URI:** `http://www.southlacrosse.org.uk/rest/tables/mens[.xml|.html]`

## Response Sample

HTML request URI (click to see actual response): [www.southlacrosse.org.uk/rest/tables/mens/Prem.html](http://www.southlacrosse.org.uk/rest/tables/mens/Prem.html)

XML request URI: `http://www.southlacrosse.org.uk/rest/tables/mens/Prem`

XML response data (edited for brevity):

```xml
<?xml version="1.0" encoding="UTF-8"?>
<league-tables>
  <division>
    <division-name short="Prem">Premier Division</division-name>
    <team>
      <team-name>Hampstead</team-name>
      <won>7</won>
      <drawn>0</drawn>
      <lost>0</lost>
      <points>21</points>
      <for>97</for>
      <against>47</against>
    </team>
    <team>
      <team-name>Purley</team-name>
      <won>6</won>
      <drawn>0</drawn>
      <lost>2</lost>
      <points>20</points>
      <for>103</for>
      <against>45</against>
    </team>
    ...(more teams)
  </division>
</league-tables>
```