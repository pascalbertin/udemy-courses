<div>
    <h3>Nowa notatka</h3>
    <div>
        <form action="/?action=create" method="POST" class="note-form">
            <ul>
                <li>
                    <label>Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" class="field-long"/>
                </li>
                <li>
                    <label>Treść</label>
                    <textarea name="description" id="field5" 
                    class="field-long field-textarea"></textarea>
                </li>
                <li>
                    <input type="submit" value="Zapisz">
                </li>
            </ul>
        </form>
    </div>
</div>