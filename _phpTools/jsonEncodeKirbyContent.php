<?php
use Kirby\Cms;
use Kirby\Cms\Page;
use Kirby\Cms\StructureObject;
include_once '_phpTools/string.php';

function getJsonEncodeFromSectionTypePlan(Page $page): array
{
    return [
        'status'=> $page->status(),
        'type'  => 'plan',
        'title' => $page->title()->value(),
        'text'  => $page->text()->value(),
        'zoneReception'       => $page->zoneReception()->value(),
        'zoneLearningLab'     => $page->zoneLearningLab()->value(),
        'zoneMakerLab'        => $page->zoneMakerLab()->value(),
        'zoneFoodLab'         => $page->zoneFoodLab()->value(),
        'zoneSchool'          => $page->zoneSchool()->value(),
        'zoneNursery'         => $page->zoneNursery()->value(),
        'zoneEntreprises'     => $page->zoneEntreprises()->value(),
    ];
}

function getJsonEncodeFromSectionTypeTeam(Page $page): array
{
    return [
        'status'=> $page->status(),
        'type'  => 'team',
        'title' => $page->title()->value(),
        'text'      => $page->text()->kirbyText()->value(),
        'partners'  => $page->partners()->toStructure()->map(
            fn($partnersItem) => getPartenersStructure($partnersItem)
        )->data(),
    ];
}


function getJsonEncodeFromSectionTypeIntroduction(Page $page): array
{
    return [
        'status'=> $page->status(),
        'type'  => 'introduction',
        'title' => $page->title()->value(),
        'cover' => getImageArrayDataInPage($page),
        'text'  => $page->text()->value(),
        'articles' => $page->articles()->toStructure()->map(function($articleItem) {
            return [
                'image' => getImageArrayDataInPage( $articleItem ),
                'items' => $articleItem->items()->toStructure()->map(function ($textArticleItem) {
                    return $textArticleItem->text()->value();
                })->data(),
            ];
        })->data(),
    ];
}

function getJsonEncodeFromSectionTypeFoundation(Page $page): array
{
    return [
        'status'=> $page->status(),
        'type'  => 'foundation',
        'title' => $page->title()->value(),
        'cover' => getImageArrayDataInPage($page),
        'text'  => $page->text()->kirbytext()->value(),
        'team'      => $page->team()->toStructure()->map(
            fn($teamMemberItem) => getTeamItemStructure($teamMemberItem)
        )->data(),
        'conseil'      => $page->conseil()->toStructure()->map(
            fn($teamMemberItem) => getPartenersStructure($teamMemberItem)
        )->data(),
    ];
}

function getJsonEncodeFromSectionTypeEvolution(Page $page): array
{
    return [
        'status'=> $page->status(),
        'type'      => 'evolution',
        'title'     => $page->title()->value(),
        'gallery'   => getImageArrayDataInPage($page),
        'text'      => $page->text()->kirbytext()->value(),
        'timeline'  => $page->timeline()->toStructure()->map(
            fn($timelineItem) => getTimelineItemStructure($timelineItem)
        )->data(),
    ];
}

// todo: getJsonEncodeFromSectionTypeContact()

function getImageArrayDataInPage(Page|StructureObject $page): ?array
{
    return count($page->cover()->toFiles()->toArray()) > 0 ? $page->cover()->toFiles()->map(
        fn($file) => getJsonEncodeImageData($file)
    )->data() : null;
}

function getJsonEncodeImageData(Cms\File $file): array
{
    return [
        'url'       => $file->url(),
        'mediaUrl'  => $file->mediaUrl(),
        'width'     => $file->width(),
        'height'    => $file->height(),
        'resize'    => [
            'tiny'      => $file->resize(50, null, 10)->url(),
            'small'     => $file->resize(500)->url(),
            'reg'       => $file->resize(1280)->url(),
            'large'     => $file->resize(1920)->url(),
        ]
    ];
}

function getTimelineItemStructure(StructureObject $timelineItem): array {
    return [
        "date"          => $timelineItem->date()->value(),
        "title"         => $timelineItem->name()->value(),
        "categories"    => $timelineItem->categories()->value(),
        "text"          => $timelineItem->text()->value(),
        'cover'         => getImageArrayDataInPage($timelineItem),
    ];
}

function getTeamItemStructure(StructureObject $teamMemberItem): array {
    return [
        'name'    => $teamMemberItem->name()->value(),
        'topic'   => $teamMemberItem->topic()->value(),
        'link'    => $teamMemberItem->link()->value(),
        'cover'   => getImageArrayDataInPage($teamMemberItem),
        'text'    => reverseMail($teamMemberItem->text()->value()),
    ];
}

function getPartenersStructure(StructureObject $teamMemberItem): array {
    return [
        'name'    => $teamMemberItem->name()->value(),
        'topic'   => $teamMemberItem->topic()->value(),
        'link'    => $teamMemberItem->link()->value(),
        'text'    => reverseMail($teamMemberItem->text()->value()),
    ];
}
