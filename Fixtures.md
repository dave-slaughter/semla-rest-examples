# Fixtures

[Error Codes](Errors.md)

Data is currently only returned as HTML. If you would like a different format please email [webmaster@southlacrosse.org.uk](mailto:webmaster@southlacrosse.org.uk).

## Request

### Format 1 - Fixtures for a team

**URI:** `http://www.southlacrosse.org.uk/rest/fixtures/mens/{team}.html`

**team**  is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#clubs)).

### Format 2 - Fixtures for a club

**URI:** `http://www.southlacrosse.org.uk/rest/clubs/{club-id}/fixtures.html`

**club-id** is required (see [allowed values](http://www.southlacrosse.org.uk/rest/#clubs)).

### Format 3 - all fixtures

**URI:** `http://www.southlacrosse.org.uk/rest/fixtures/mens.html`

## Response Samples

HTML request URI (click to see actual response): [www.southlacrosse.org.uk/rest/fixtures/mens/Purley.html](http://www.southlacrosse.org.uk/rest/fixtures/mens/Purley.html)
