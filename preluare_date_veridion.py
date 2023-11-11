import requests
import json
import csv

def get_data_from_api(api_url, bearer_token, payload):
    headers = {
        'x-api-key': bearer_token,
        'Content-Type': 'application/json',  # Adjust content type based on the API requirements
    }

    response = requests.post(api_url, headers=headers, json=payload)
    if response.status_code == 200:
        return response.json()
    else:
        print(f"Failed to fetch data from the API. Status code: {response.status_code}")
        return None

def write_to_csv(data, csv_file, fields_to_extract):
    if not data:
        print("No data to write.")
        return

    # Extracting data from the "result" list
    companies_data = data.get("result", [])

    # Modify the following code based on the actual structure of the API response
    headers = list(companies_data[0].keys())

    with open(csv_file, 'w', newline='', encoding='utf-8') as csvfile:
        writer = csv.DictWriter(csvfile, fieldnames=headers)
        
        writer.writeheader()
        for row_data in companies_data:
            # Encode non-ASCII characters using UTF-8
            encoded_row_data = {field: json.dumps(row_data.get(field, ''), ensure_ascii=False) for field in fields_to_extract}
            writer.writerow(encoded_row_data)

    print(f"Data successfully written to {csv_file}")

if __name__ == "__main__":
    api_url = 'https://data.soleadify.com/search/v1/companies?page_size=10'
    
    bearer_token = 'pXStedvXkA9pMcNK1tWvx_4DesmTsIZ47qfTa6WkqFxgrCvCqJA0mpALQ53J'
    pagination_token = ''
    
    # Include all relevant fields in the "fields_to_extract" list
    fields_to_extract = [
        "soleadify_id", "company_name",
        "company_legal_names", "company_commercial_names", "main_country_code",
        "main_country", "main_region", "main_city", "main_street",
        "main_street_number", "main_postcode", "main_latitude", "main_longitude",
        "locations", "num_locations", "company_type", "year_founded",
        "employee_count", "estimated_revenue", "short_description", "long_description",
        "business_tags", "main_business_category", "main_industry", "main_sector",
        "primary_phone", "phone_numbers", "primary_email", "emails", "other_emails",
        "website_url", "website_domain", "website_tld", "website_language_code",
        "facebook_url", "twitter_url", "instagram_url", "linkedin_url",
        "ios_app_url", "android_app_url", "youtube_url", "cms", "alexa_rank",
        "technologies", "naics_2022", "nace_rev2", "ncci_codes_28_1", "sic", "isic_v4",
        "sics_industry", "sics_subsector", "sics_sector", "ibc_insurance",
        "created_at", "last_updated_at"
    ]

    payload = {
        "filters": [
            {
                "attribute": "company_location",
                "relation": "in",
                "value": [
                    {
                        "country": "Romania"
                    }
                ],
                "strictness": 2
            },
            {
                "attribute": "company_industry",
                "relation": "in",
                "value": [
								"Health & Medical",
						]
            }
        ]
    }
        
    for i in range(2):
        csv_file = 'output_nr' + str(i) + '.csv'
        api_data = get_data_from_api(api_url + pagination_token, bearer_token, payload)
        if not api_data or "pagination" not in api_data:
            print("Error in API response or no pagination information.")
            break
        
        pagination_token = api_data["pagination"].get("next", '')
        if not pagination_token:
            print("No more data to fetch.")
            break

        write_to_csv(api_data, csv_file, fields_to_extract)
