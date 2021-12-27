const bodyParser = require('body-parser')
const app = require('express')()
const { $content } = require('@nuxt/content')

app.use(bodyParser.json())
app.all('/itemsByCategory', async (req, res) => {
  let query = $content('items', { deep: true })
    .sortBy('id', 'asc')
  const allItems = await query.fetch();
  const itemsByCategory = {};

  for (const currentItem of allItems) {
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