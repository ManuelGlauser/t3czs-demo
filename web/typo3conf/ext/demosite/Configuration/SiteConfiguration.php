<?php
// This works just like AdditionalConfiguration.php
// In fact, it will be included into the local 'AdditionalConfiguration.php'

// Use this file to (for instance) configure the site for various
// application contexts and/or to override third-party extensions'
// configuration that would usually be written by the extension
// manager ()

// Examples:
// - Route emails to a local mbox file on dev/local contexts
// - Dynamically set the default 'mail from name' depending on the current domain.
// - Generate the 'trustedHostsPattern' dynamically
// - Configure different logging mechanisms depending on the application context