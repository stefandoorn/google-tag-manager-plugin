function GaEvent(category)
{
    this.category = category;
}

GaEvent.prototype.register = function(action, label, value)
{
    window.dataLayer.push({
        'event': 'GAEvent',
        'eventCategory': this.category,
        'eventAction': action,
        'eventLabel': label,
        'eventValue': value
    });
};
