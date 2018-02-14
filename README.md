# AssetCollectionHelpers

This Neos package solves some problems when dealing with AssetCollections nodes.

In Neos, it is possible to select any entity in the `SelectBoxEditor`. AssetCollections in Neos are entities and they are very useful if you want to provide file list or galleries without selecting each file manually.

The only problem is that the content cache is not aware of the following changes:

* An Asset of an AssetCollection is edited/deleted/replaced
* An Asset is added to/removed from an AssetCollection

Therefore, if you e.g. upload a new Asset to an existing AssetCollection, your file list will still display only the files present when the cache was last built.

This package provides you with the necessary implementations to work around these problems.

### Example NodeType configuration

Use AssetCollection as in a `SelectBoxEditor`:

```
'My.FoobarCom:Content.FileList':
  [...]
  properties:
    assetCollection:
      type: 'Neos\Media\Domain\Model\AssetCollection'
      ui:
        label: i18n
        inspector:
          group: 'resources'
          editor: 'Neos.Neos/Inspector/Editors/SelectBoxEditor'
          editorOptions:
            dataSourceIdentifier: visol-neos-assetcollections
        reloadIfChanged: true
 
```

### Example fusion caching configuration

In Fusion, we add a cache tag for the given AssetCollection:

```
prototype(My.FoobarCom:Content.FileList) < prototype(Neos.Neos:ContentComponent) {
    assetCollection = ${q(node).property('assetCollection')}
    @context.assetCollection = ${this.assetCollection}

    @cache {
        mode = 'cached'
        entryIdentifier {
            node = ${node}
        }
        entryTags {
            1 = ${'Node_' + node.identifier}
            2 = ${AssetCollection.Caching.assetCollectionTag(assetCollection)}
        }
    }
    
    [... configure output]
}
```

The package then monitors changes to assets and asset collections and flushes the cache of all nodes having the affected cache tag.

### Credits

https://discuss.neos.io/t/guide-how-to-select-entities-in-the-inspector-neos-2-3-lts/1664

visol digitale Dienstleistungen GmbH, www.visol.ch
