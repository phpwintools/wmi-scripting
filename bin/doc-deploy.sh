yarn docs:build && cd docs/.vuepress/dist && git init &&  git add -A && git commit -m "deploy" && git push -f https://github.com/phpwintools/wmi-scripting.git master:gh-pages

cd -
