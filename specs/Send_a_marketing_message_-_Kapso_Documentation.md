**Title:** Send a marketing message - Kapso Documentation

**Author:** ​X-API-Keystringheaderrequired

**Source:** [https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-marketing-message](https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-marketing-message)

---

# Page Structure Map
```text
Send a marketing message - Kapso Documentation
└── Body
```

---

Project API key for authentication. This is the recommended authentication method.

Get your API key from the Kapso dashboard under Integrations > API keys.

phone\_number\_id

string

required

WhatsApp Business Phone Number ID

#### Body

application/json

-   Option 1

-   Option 2

messaging\_product

enum<string>

required

Always "whatsapp"

Available options:

`whatsapp`

Example:

`"whatsapp"`

to

string

required

Recipient phone number. Use `recipient` for BSUID sends.

Example:

`"15551234567"`

type

enum<string>

required

Marketing messages must use a WhatsApp template.

Available options:

`template`

Example:

`"template"`

template

object

required

Show child attributes

recipient\_type

enum<string>

default:individual

Marketing messages are sent to individual recipients.

Available options:

`individual`

biz\_opaque\_callback\_data

string

Arbitrary string for tracking (echoed in webhooks)

Maximum string length: `512`

recipient

string

Recipient BSUID or parent BSUID. If `to` is also present, the phone number in `to` takes precedence.

Example:

`"US.13491208655302741918"`

Message accepted

messaging\_product

enum<string>

required

Available options:

`whatsapp`

Example:

`"whatsapp"`

contacts

object\[\]

required

Show child attributes

messages

object\[\]

required

Show child attributes

Was this page helpful?

Send a marketing message