Disables form fields before submitting if they have the data-default-value attribute.
                            

## Code sample

```
{% include molecule('form-input-default-value-disabler') with {
    attributes: {
        'form-selector': 'form-selector',
        'input-selector': 'input-selector',
        'default-value-attribute': 'default-value-attribute'
    }
} only %}
```