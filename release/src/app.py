import sys

if sys.version_info < (3, 13):
    raise RuntimeError("Python 3.13以上で動作します。")

print("Hello")
