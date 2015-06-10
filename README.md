# puzzle-piece
A PHP puzzle piece image generator

# examples

To generate image files of puzzle pieces:
```PHP
$pieceSize = 50;
$pieceMargin = 10;

$piece = new Piece('../img.jpg', $pieceSize, $pieceMargin);

for ($i = 0; $i <= $piece->getMaxElementsX(); $i++) {
    for ($j = 0; $j <= $piece->getMaxElementsY(); $j++) {
        // the output will be save in "output" directory with the name "i-j.gif"
        $piece->output($i, $j, 'output/' . $i . '-' . $j . '.gif');
    }
}
```

To display the puzzle piece image directly:

```PHP
$pieceSize = 50;
$pieceMargin = 10;

$piece = new Piece('../img.jpg', $pieceSize, $pieceMargin);

// the output will also generate GIF headers
$piece->output($x, $y);
```
----

DISCLAIMER

IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
