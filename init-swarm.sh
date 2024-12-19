#!/bin/bash
# 使用 Domain Name 作為管理節點地址
MANAGER_DOMAIN="local.karllin.com"

# 初始化 Swarm，使用域名
docker swarm init --advertise-addr $MANAGER_DOMAIN

# 輸出 Token
echo "Swarm join token for workers:"
docker swarm join-token worker
