const {select, subscribe} = wp.data;

//Get all all register block
const allBlocks = select('core/block-editor').getBlocks();


//Subscribe run in every change in editor. Source from The post grid 
wp.data.subscribe(() => {
    try {
        const {getBlockOrder, getBlock} = wp.data.select('core/block-editor');
        const blockIds = getBlockOrder();
        const blocks = blockIds.map((blockId) => getBlock(blockId))
        const ourBlocks = blocks.filter(item => ['rttpg/tpg-grid-layout', 'rttpg/tpg-grid-hover-layout', 'rttpg/tpg-list-layout', 'rttpg/tpg-slider-layout', 'rttpg/row', 'rttpg/tpg-section-title', 'rttpg/tpg-slider-layout'].includes(item.name));
        const _wp$data$select = wp.data.select("core/editor");
        if (!_wp$data$select || 0 == ourBlocks.length) {
            return;
        }
        const isSavingPost = _wp$data$select.isSavingPost;
        const isAutoSavingPost = _wp$data$select.isAutosavingPost;

        if (isSavingPost() && !isAutoSavingPost()) {
            ParseCss();
        }
    } catch (err) {
        console.error(err);
    }
});