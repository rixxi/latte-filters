You can now stop spamming prepareTemplateFilters and setup with template runtime helpers setup!
This extension allows you to add dynamic filters (runtime helpers and helper loaders) to latte
engine just by tagging service.

```php
class DynamicHelperWithFewFilters
{

	function money($value) { /* ... */ }

	function formatTime($value) { /* ... */ }

}
```

```neon
service:
	-
		class: DynamicHelperWithFewFilters
		tags:
			latte.filter: [ money, formatTime: time ]

extensions:
	latteFilters: Rixxi\LatteFilters\DI\LatteFiltersExtension
```
