cd /opt/lampp/htdocs/site/database/migrations/tenant/

# Correction plus agressive pour toutes les migrations
for file in *.php; do
    # Commenter les foreign keys vers tenants (sur plusieurs lignes)
    sed -i '/\$table->foreign.*tenant_id.*/,/->onDelete.*cascade.*/s/^/\/\/ /' "$file"
    sed -i '/\$table->foreign.*\[\x27tenant_id\x27\]/,/->onDelete.*cascade.*/s/^/\/\/ /' "$file"
    
    # Commenter les unique constraints avec tenant_id
    sed -i '/\$table->unique.*tenant_id.*/s/^/\/\/ /' "$file"
    
    # Remplacer les foreignId tenant_id par nullable
    sed -i 's/\$table->foreignId(\x27tenant_id\x27)->constrained()/\$table->unsignedBigInteger(\x27tenant_id\x27)->nullable()/g' "$file"
    sed -i 's/\$table->foreignId(\x27tenant_id\x27)/\$table->unsignedBigInteger(\x27tenant_id\x27)->nullable()/g' "$file"
    
    echo "Corrigé: $file"
done