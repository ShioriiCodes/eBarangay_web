/**
 * Resident document request wizard: category → sub-type → dynamic fields.
 *
 * @param {object} payload
 * @param {Array<{key: string, label: string, group: string, subtypes: Array<{key: string, label: string}>, fieldsBySubtype: Record<string, Array<{name: string, type: string, label: string, required: boolean, max: number}>>}>} payload.categories
 * @param {object} payload.old
 */
export function registerDocumentRequestForm(AlpineInstance) {
    AlpineInstance.data('documentRequestForm', (payload) => ({
        categories: payload?.categories ?? [],
        docType: payload?.old?.document_type ?? '',
        subtype: payload?.old?.request_subtype ?? '',
        purpose: payload?.old?.purpose ?? '',
        additional_details: payload?.old?.additional_details ?? '',
        formFields: { ...(payload?.old?.form_fields ?? {}) },

        init() {
            this.$watch('docType', () => {
                this.subtype = '';
                this.formFields = {};
                this.syncSingleSubtype();
            });
            this.$watch('subtype', () => {
                this.pruneFormFields();
            });
            this.syncSingleSubtype();
            this.pruneFormFields();
        },

        get currentCategory() {
            return this.categories.find((c) => c.key === this.docType) ?? null;
        },

        get needsSubtypePicker() {
            return (this.currentCategory?.subtypes?.length ?? 0) > 1;
        },

        get activeFields() {
            const cat = this.currentCategory;
            if (!cat) {
                return [];
            }
            let key = this.subtype;
            if (!key && (cat.subtypes?.length ?? 0) === 1) {
                key = cat.subtypes[0].key;
            }
            if (!key) {
                return [];
            }
            return cat.fieldsBySubtype?.[key] ?? [];
        },

        syncSingleSubtype() {
            const cat = this.currentCategory;
            if (!cat || (cat.subtypes?.length ?? 0) !== 1) {
                return;
            }
            const only = cat.subtypes[0].key;
            if (!this.subtype) {
                this.subtype = only;
            }
        },

        pruneFormFields() {
            const allowed = new Set(this.activeFields.map((f) => f.name));
            Object.keys(this.formFields).forEach((k) => {
                if (!allowed.has(k)) {
                    delete this.formFields[k];
                }
            });
        },
    }));
}
