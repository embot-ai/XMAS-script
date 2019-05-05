# XMAS-script
The eXtensible Message Audio and SSML syntax

### Introduction

This guide describes the e**X**tensible **M**essage **A**udio and **S**SML syntax or shortly X&middot;M&middot;A&middot;S script.
It is a very simple to use encoding to separate multiple fields or media payloads in chatbot response strings.
X&middot;M&middot;A&middot;S script was founded by Serge Cornelissen at December 24th, 2018.
It thanks its name not only because it was just before Christmas;
It owes his name especially to indicate the message (M), audio (A) and SSML (S) fields in chatbot responses we use at Embot.

The goal when designing X&middot;M&middot;A&middot;S script, was to have a syntax that is:
* Editable by hand
* Easy to remember
* Lightweight and fast to parse
* Extensible and flexible enough for future fields and features
* Keeping fields in correct order
* Not conflicting with other frequently used markup characters, like `{}` `[]` `<>`

As a developer, XMAS script offers a simple method to use multiple data fields in single string responses.
It is much easier then passing, encoding and decoding JSON or XML objects internally arround the systems.
If you want to append a sentence to the response, just echo an extra field.
You don't have to decode the previous payload first.
Decoding is only done in the final stage or frontend.

### Syntax

X&middot;M&middot;A&middot;S script uses a very simple 3 character syntax to indicate a field type `#X:`, where the letter `X` should be replaced by the field type character:

| Encoding | Field type                                |
|----------|-------------------------------------------|
| `#X:`    | General format of a XMAS script separator |
| `#M:`    | Message (text)                            |
| `#A:`    | Audio                                     |
| `#S:`    | SSML                                      |



#### Basic usage examples

Input of the XMAS parser:

```xmas
#M:Hello World!#A:hello-world.mp3

```

The output of the XMAS parser can be presented in many ways.
When we use our` XMAS Script Output Layer` as final stage of the chatbot's conversation endpoint, the output will be a JSON array:

```
[
    [
        "M",
        "Hello World!"
    ],
    [
        "A",
        "hello-world.mp3"
    ]
]
```

&nbsp;
&nbsp;

Here's another example using a message and SSML field:

```xmas
#M:Hello World!#S:<speak>Hello World!</speak>

```

```
[
    [
        "M",
        "Hello World!"
    ],
    [
        "S",
        "<speak>Hello World!<\/speak>"
    ]
]
```

&nbsp;
&nbsp;

Please note XMAS script is perfectly capable handling new-line and whitespace characters in fields, and trims whitespace around field content borders

```xmas
#M:Hello World!   #S:

<speak>
	Hello World!
</speak>

```

```
[
    [
        "M",
        "Hello World!"
    ],
    [
        "S",
        "<speak>\n\tHello World!\n<\/speak>"
    ]
]
```

#### Ending fields and keep order

XMAS script will treat any text part as a message field by default.
For instance, a string with the contents of `Hello World!` will be treated by the XMAS parser the same as `#M:Hello World!`.
To end a field type and fallback to this default behavior, you can use the prereserved `#E:` tag.
This allows you to write stings as:

```plain
Hi!#A:ping.wav#E:How are you?
```

```
[
    [
        "M",
        "Hi!"
    ],
    [
        "A",
        "ping.wav"
    ],
    [
        "M",
        "How are you?"
    ]
]
```


```plain
Hi! #A:ping.wav #E:How are you?
#M:Hi! #A:ping.wav #M:How are you?
#M:Hi!#E: #A:ping.wav#E: #M:How are you?#E:
```


The example above illustrate that XMAS script parses fields in order.
The sound `ping.wav` is played after `Hi!` and before `How are you?`.
The examples also illustrate that you can have multiple fields of the same type.
When calling the **displayText()** method of the XMAS script library, all the message (M) fields will be combined as one string separated by spaces `Hi! How are you?`


### Extending the language

The advantage of X&middot;M&middot;A&middot;S script is that it is expandable. You can choose new letters for your own datatypes.
For example, to distinguish JSON from message data, you might want to use a `#J:` separator as syntax.

```xmas
#M:Hello World!#J:{"say":"Hello World!"}

```

```
[
    [
        "M",
        "Hello World!"
    ],
    [
        "J",
        "{\"say\":\"Hello World!\"}"
    ]
]
```


### Signal fields

A signal field is a special field to submit meta data with the message.
It is not presented to the user like a message or audio field.
A signal field starts with `#!:` and is normally a `<key>=<value>` pair


#### Usage

For instance if you want to give the message a tag name 'greeting' for analytical purposes, you can use

```plain
Hello world!#!:tag=greeting
```

```
[
    [
        "M",
        "Hello world!"
    ],
    [
        "!",
        "tag=greeting"
    ]
]
```

Or maybe you want to use a signal to close the microphone in a voice assistant.

```plain
Hello world!#!:googlehome.param.expectUserResponse=false
```


### Future feature table


| Encoding | Field type                                          |
|----------|-----------------------------------------------------|
| `#X:`    | General format of a XMAS script separator, XML data |
| `#M:`    | Message (text)                                      |
| `#A:`    | Audio                                               |
| `#S:`    | SSML                                                |
| &nbsp;   |                                                     |
| `#E:`    | End field Indicator (reserved)                      |
| `#J:`    | JSON                                                |
| `#!:`    | Signals, Variables, Commands                        |

&nbsp;

*XMAS script is licensed under the Apache License, Version 2.0*

*For further information visit [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)*

