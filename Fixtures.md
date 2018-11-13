# Fixtures

[Error Codes](Errors.md)

Data is returned as XML by default, or if the extension `xml`, and HTML if the extension is `html`.

## Request

### Format 1 - Fixtures for a team

**URI:** `http://www.southlacrosse.org.uk/rest/fixtures/mens/{team}[.xml|.html]`

**team**  is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#clubs)).

### Format 2 - Fixtures for a club

**URI:** `http://www.southlacrosse.org.uk/rest/clubs/{club-id}/fixtures[.xml|.html]`

**club-id** is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#clubs)).

### Format 3 - all fixtures

**URI:** `http://www.southlacrosse.org.uk/rest/fixtures/mens[.xml|.html]`

## Response Samples

HTML request URI (click to see actual response): [www.southlacrosse.org.uk/rest/fixtures/mens/Purley.html](http://www.southlacrosse.org.uk/rest/fixtures/mens/Purley.html)

XML request URI: `http://www.southlacrosse.org.uk/rest/fixtures/mens/Purley`

XML response data (edited for brevity):

```xml
<?xml version="1.0"?>
<fixtures created="2011-07-25T12:00:00">
  <fixture>
    <date>2011-10-01</date>
    <home-team>Purley</home-team>
    <away-team>Hillcroft</away-team>
    <competition>Prem</competition>
  </fixture>
  <fixture>
    <date>2011-10-08</date>
    <home-team>Cardiff Harlequins</home-team>
    <away-team>Purley</away-team>
    <competition>Prem</competition>
  </fixture>
...
  <fixture>
    <date>2011-10-29</date>
    <home-team>Welwyn Warriors</home-team>
    <away-team>Purley</away-team>
    <competition>Prem</competition>
    <time>1:30pm</time>
  </fixture>
...
  <fixture>
    <date>2012-04-21</date>
    <competition>Flags Final</competition>
  </fixture>
</fixtures>
```

Note that:

*   `time` elements are optional. The default time for men's games is 2:00pm.
*   Some fixtures only contain a `competition`, i.e. without home or away teams. This is either for tournaments (where many teams are competing), or for round robin competitions where later rounds are yet to be decided (such at the flags), or for events which all teams should be aware of.