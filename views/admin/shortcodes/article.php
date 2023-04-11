<tr class="active article-item">
    <th scope="row">
        <?= $article['id'] ?>
    </th>
    <td>
        <a href="/admin/edit?id=<?= $article['id'] ?>">
            <?= $article['title'] ?>
        </a>
    </td>
    <td class="text-right">
        <i class="fa fa-times init-article-delete" aria-hidden="true" data-toggle="modal"
            data-target=".bs-example-modal-sm" data-item-id="<?= $article['id'] ?>"></i>
    </td>
</tr>