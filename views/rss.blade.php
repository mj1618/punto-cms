<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">
{{--{!! '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" !!}--}}
{{--<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">--}}
    <channel>
        <title>{{ $channel['title'] }}</title>
        <link>{{ $channel['link'] }}</link>
        <description>{{ $channel['description'] }}</description>
{{----}}{{--<atom:link href="{{ Request::url() }}" rel="self"></atom:link>--}}
{{----}}{{--@if (!empty($channel['logo']))--}}
{{--        --}}{{--<image>--}}
{{--        --}}{{--<url>{{ $channel['logo'] }}</url>--}}
{{--        --}}{{--<title>{{ $channel['title'] }}</title>--}}
{{--        --}}{{--<link>{{ $channel['link'] }}</link>--}}
{{--        --}}{{--</image>--}}
{{----}}{{--@endif--}}
{{--        <language>{{ $channel['lang'] }}</language>--}}
        <lastBuildDate>{{ $channel['pubdate'] }}</lastBuildDate>
{{----}}@foreach($items as $item)
        <item>
            <title>{{ $item['title'] }}</title>
{{--    --}}{{--<link>{{ $item['link'] }}</link>--}}
{{--    --}}{{--<guid isPermaLink="true">{{ $item['link'] }}</guid>--}}
            <description>{!! $item['description'] !!}</description>
{{--    --}}@if (!empty($item['content']))
            <content:encoded><![CDATA[{!! $item['content'] !!}]]></content:encoded>
{{--    --}}@endif
{{--    --}}{{--<dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">{{ $item['author'] }}</dc:creator>--}}
            <pubDate>{{ DateTime::createFromFormat('Y-m-d\TH:i:sP',str_replace('+0800','AWST',$item['pubdate']))->format('D, d M Y H:i:s O') }}</pubDate>
        </item>
{{----}}@endforeach
    </channel>
</rss>