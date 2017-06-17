<?php
$regex = '{
    \A        # the absolute beginning of the string
    \h*        # optional horizontal whitespace
    (        # start of group 1 (this is called recursively)
    (?:
        \(        # literal (

        \h*
        [-+]?        # optionally prefixed by + or -
        \h*

        # A number
        (?: \d* \. \d+ | \d+ \. \d* | \d+) (?: [eE] [+-]? \d+ )?

        (?:
            \h*
            [-+*/]        # an operator
            \h*
            (?1)        # recursive call to the first pattern.
        )?

        \h*
        \)        # closing )

        |        # or: just one number

        \h*
        [-+]?
        \h*

        (?: \d* \. \d+ | \d+ \. \d* | \d+) (?: [eE] [+-]? \d+ )?
    )

    # and the rest, of course.
    (?:
        \h*
        [-+*/]
        \h*
        (?1)
    )?
    )
    \h*

    \z        # the absolute ending of the string.
}x';
?>