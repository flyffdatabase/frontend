const bodyParser = require('body-parser')
const app = require('express')()
const { $content } = require('@nuxt/content')
const { $http } = require('@nuxt/http')

app.use(bodyParser.json())
app.get('/monsters/:monsterId', async (req, res) => {
  let monsterId = req.params.monsterId;
  let monster
  try {
    monster = await $content('monsters', monsterId).fetch()
  } catch (e) {
    return error({ message: 'Monster ' + monsterId + ' not found' })
  }

  let dropItems = {
    common: [],
    uncommon: [],
    rare: [],
    veryrare: [],
    unique: []
  };
  for (const currentDrop of monster.drops) {
    let droppedItem;
    try {
      droppedItem = await $content('items', 'item_' + currentDrop.item).fetch();
      dropItems[droppedItem.rarity].push(droppedItem);
    } catch (e) {
      return error({ message: 'Dropped Item ' + currentDrop.item + ' not found' })
    }
  }

  res.json({ monster: monster, dropItems: dropItems });
});

app.get('/gameVersion', async (req, res) => {
  let gameDataVersion = await $content('gameVersion').fetch();
  res.json({
    gameDataVersion: gameDataVersion.gameDataVersion
  });
});

app.get('/items/:itemId', async (req, res) => {
  let itemId = req.params.itemId;
  
  let item;
  try {
    item = await $content('items', itemId).fetch()
  } catch (e) {
    return error({ message: 'Item ' + itemId + ' not found' })
  }

  let droppingMonsters = [];
  for (const currentMonsterId of item.flyffdb_dropped_by) {
    let monster;
    try {
      monster = await $content('monsters', 'monster_' + currentMonsterId).fetch();
      droppingMonsters.push(monster);
    } catch (e) {
      return error({ message: 'Monster ' + currentMonsterId + ' not found' })
    }
  }

  droppingMonsters.sort((a, b) => (a.level > b.level) ? 1 : -1);
  
  res.json({
    item,
    droppingMonsters
  });
});

app.all('/itemsByCategory', async (req, res) => {
  let query = $content('items', { deep: true })
    .sortBy('id', 'asc')
  const allItems = await query.fetch();
  const itemsByCategory = {};

  for (const currentItem of allItems) {
    if (!currentItem.subcategory || currentItem.subcategory == 'blinkwing' || currentItem.category == 'scroll') {
      currentItem.subcategory = currentItem.category;
      currentItem.category = 'other';
    }

    if (!itemsByCategory.hasOwnProperty(currentItem.category)) {
      itemsByCategory[currentItem.category] = {
        'name': currentItem.category,
        'subcategories': {}
      };
    }
    if (!itemsByCategory[currentItem.category].subcategories.hasOwnProperty(currentItem.subcategory)) {
      itemsByCategory[currentItem.category].subcategories[currentItem.subcategory] = {
        'name': currentItem.subcategory,
        'items': []
      };
    }

    itemsByCategory[currentItem.category].subcategories[currentItem.subcategory].items.push(currentItem);
  }

  for (const categoryName of Object.keys(itemsByCategory)) {
    for (const subcategoryName of Object.keys(itemsByCategory[categoryName].subcategories)) {
      if (categoryName == 'armor' || categoryName == 'weapon') [
        itemsByCategory[categoryName].subcategories[subcategoryName].items.sort((a, b) => (a.level > b.level) ? -1 : 1)
      ]
    }
  }
  res.json({ itemsByCategory: itemsByCategory });
})

module.exports = app