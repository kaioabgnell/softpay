**Title:** Send a message - Kapso Documentation

**Author:** ​X-API-Keystringheaderrequired

**Source:** [https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-message](https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-message)

---

# Page Structure Map
```text
Send a message - Kapso Documentation
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

-   Option 3

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

Recipient phone number or group ID. Use `recipient` for BSUID sends.

Example:

`"15551234567"`

type

enum<string>

required

Type of WhatsApp message

Available options:

`text`,

`image`,

`video`,

`audio`,

`document`,

`sticker`,

`location`,

`contacts`,

`interactive`,

`template`,

`reaction`

recipient\_type

enum<string>

default:individual

Recipient type. Use `individual` for 1:1, `group` for group messages. When `group`, `to` must be a Group ID obtained via the Groups API.

Available options:

`individual`,

`group`

biz\_opaque\_callback\_data

string

Arbitrary string for tracking (echoed in webhooks)

Maximum string length: `512`

recipient

string

Recipient BSUID or parent BSUID. If `to` is also present, the phone number in `to` takes precedence.

Example:

`"US.13491208655302741918"`

context

object

Reply context

Show child attributes

text

object

Show child attributes

image

object

Show child attributes

video

object

Show child attributes

audio

object

Show child attributes

document

object

Show child attributes

sticker

object

Show child attributes

location

object

Show child attributes

contacts

object\[\]

Show child attributes

interactive

object

Show child attributes

template

object

Show child attributes

reaction

object

Show child attributes

Message sent successfully

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