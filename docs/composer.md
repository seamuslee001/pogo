# Pogo and Composer

Internally, `pogo` uses `composer` to download the dependencies and store them in a hidden folder.
Understanding this mechanism may be useful if you are integrating with other development tooling (IDEs, debuggers, packagers, etc).


By default, `pogo` will put dependencies in `$HOME/.cache/pogo/<digest>`, where `<digest>` is a computed value
that depends on your list of requirements. You may tune the defaults with the `POGO_BASE` variable, e.g.

* `POGO_BASE=/var/cache/pogo`: Store builds in a shared folder
* `POGO_BASE=.`: Store builds in a dot-folder, adjacent to the executed script.

For a specific script, you may optionally exercise fine-grained control over the dependency
downloads, as in any of these:

```bash
## Download dependencies to a specific folder - and run the script.
pogo run <script-file> -o=<dep-dir>

## Download dependencies to a specific folder.
pogo dl <script-file> -o=<dep-dir>

## Update dependencies in a previously downloaded folder
## Equivalent to re-running "pogo dl <same-script> -o=<same-output>"
cd <dep-dir>
pogo up
```
