$(document).ready(function () {
    $('.translate-btn').on('click', function () {
        const comment = $(this).data('comment');
        translateComment(comment);
    });

    function translateComment(comment) {
        const url = "https://api.openai.com/v1/chat/completions";
        const apiKey = '';//chat gpt api key koyulması gerekmektedir

        const data = {
            "model": "gpt-3.5-turbo",
            "messages": [
                {
                    "role": "system",
                    "content": "You are a translator. You will translate user's message and send the translated text back to user. You'll only answer with users translated text."
                },
                {
                    "role": "user",
                    "content": comment
                },
            ]
        }

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`
            },
            body: JSON.stringify(data)
        };

        var generatedText = "";
        fetch(url, options)
            .then(response => response.json())
            .then(data => {
                generatedText = data.choices[0].message.content;
                $('#translated-comment').text(generatedText);
            })
            .catch(error => {
                console.error('Error:', error);
            });

        return generatedText;
    }
});
