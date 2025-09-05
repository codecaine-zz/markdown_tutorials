/* Minimal Highlight.js language definition for the V language (vlang).
   This registers the language as 'v' so code fences like ```v get highlighted.
   It's intentionally small and conservative to avoid false positives.
*/
(function(hljs) {
  if (!hljs) return;

  hljs.registerLanguage('v', function(hljs) {
    const KEYWORDS = {
      keyword:
        'fn struct enum union interface type import module pub mut const static if else for in while match break continue ' +
        'return unsafe goto asm select none or and not else if embed ' +
        'defer throw try catch finally ' ,
      literal: 'true false none'
    };

    return {
      name: 'V',
      aliases: ['v'],
      keywords: KEYWORDS,
      illegal: /<\/(script|style)>/, // avoid accidental HTML termination
      contains: [
        hljs.C_LINE_COMMENT_MODE, // // comment
        hljs.C_BLOCK_COMMENT_MODE, // /* */
        // raw string using backticks (like Go)
        {
          className: 'string',
          begin: '`',
          end: '`'
        },
        hljs.QUOTE_STRING_MODE, // "..."
        hljs.APOS_STRING_MODE, // '...'
        // Numbers
        hljs.C_NUMBER_MODE,
        // Function definitions: fn name(
        {
          className: 'function',
          beginKeywords: 'fn',
          end: /\(/,
          excludeEnd: true,
          contains: [hljs.UNDERSCORE_TITLE_MODE]
        },
        // Types that look like identifiers starting with capital letter
        {
          className: 'type',
          begin: '\\b[A-Z][A-Za-z0-9_]*\\b'
        }
      ]
    };
  });
})(typeof hljs !== 'undefined' ? hljs : null);
