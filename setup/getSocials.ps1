try {

    $twitterToken = $env:TWITTER_API_BEARER_TOKEN
    $telegramToken = $env:TELEGRAM_API_TOKEN

    # Twitter
    $Headers = @{ "Authorization" = "Bearer $twitterToken" }
    $url = "https://api.twitter.com/2/users/by/username/Million__Token"
    $result = Invoke-RestMethod -Uri $url -Headers $Headers -Body @{ "user.fields" = "public_metrics" }
    $twitterFollowers = $result.data.public_metrics.followers_count

    # Telegram
    $url = "https://api.telegram.org/bot$telegramToken/getChatMemberCount?chat_id=@MilliontokensOfficial"
    $result = Invoke-RestMethod -Uri $url
    $telegramMembers = $result.result

    # Reddit
    $url = "https://www.reddit.com/r/milliontoken/about.json"
    $result = Invoke-RestMethod -Uri $url
    $redditSubscribers = $result.data.subscribers

    mysql -e "INSERT INTO MillionTracker.socialmedia (discord, facebook, instagram, reddit, telegram, twitter) VALUES (0, 0, 0, $redditSubscribers, $telegramMembers, $twitterFollowers);" -u cronuser --password=$env:CRONUSER_PWD

} catch {
    "[$(Get-Date -Format "U")] GetSocials# $_" | Add-Content -Path "$PSScriptRoot\Errors"
}