<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: prefeitura
//CLASSE DA ENTIDADE confsite
class cl_confsite { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $w01_cod = 0; 
   var $w01_descricao = null; 
   var $w01_corbody = null; 
   var $w01_cortexto = null; 
   var $w01_corbordamenu = null; 
   var $w01_corfundomenu = null; 
   var $w01_corfundomenuativo = null; 
   var $w01_corfontemenu = null; 
   var $w01_tamfontesite = null; 
   var $w01_bordamenu = null; 
   var $w01_estilomenu = null; 
   var $w01_fontemenu = null; 
   var $w01_tamfontemenu = null; 
   var $w01_wfontemenu = null; 
   var $w01_estilofontemenu = null; 
   var $w01_fontesite = null; 
   var $w01_wfontesite = null; 
   var $w01_estilofontesite = null; 
   var $w01_corfontesite = null; 
   var $w01_fonteativo = null; 
   var $w01_tamfonteativo = null; 
   var $w01_wfonteativo = null; 
   var $w01_estilofonteativo = null; 
   var $w01_corfonteativo = null; 
   var $w01_fonteinput = null; 
   var $w01_tamfonteinput = null; 
   var $w01_corfonteinput = null; 
   var $w01_corbordainput = null; 
   var $w01_bordainput = null; 
   var $w01_estiloinput = null; 
   var $w01_corfundoinput = null; 
   var $w01_linhafontemenu = null; 
   var $w01_linhafontesite = null; 
   var $w01_linhafonteativo = null; 
   var $w01_estilofonteinput = null; 
   var $w01_fontebotao = null; 
   var $w01_tamfontebotao = null; 
   var $w01_estilofontebotao = null; 
   var $w01_wfontebotao = null; 
   var $w01_corfontebotao = null; 
   var $w01_corfundobotao = null; 
   var $w01_bordabotao = null; 
   var $w01_estilobotao = null; 
   var $w01_corbordabotao = null; 
   var $w01_titulo = null; 
   var $w01_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w01_cod = int4 = Código 
                 w01_descricao = varchar(40) = Nome do Site 
                 w01_corbody = varchar(10) = Cor do Site 
                 w01_cortexto = varchar(10) = Cor do Texto 
                 w01_corbordamenu = varchar(10) = Cor da Borda 
                 w01_corfundomenu = varchar(10) = Cor de Fundo 
                 w01_corfundomenuativo = varchar(10) = Cor de Fundo 
                 w01_corfontemenu = varchar(10) = Cor 
                 w01_tamfontesite = varchar(10) = Tam 
                 w01_bordamenu = varchar(10) = Bordas do Menu 
                 w01_estilomenu = varchar(10) = Estilo 
                 w01_fontemenu = varchar(40) = Fonte 
                 w01_tamfontemenu = varchar(10) = Tam 
                 w01_wfontemenu = varchar(10) = Larg 
                 w01_estilofontemenu = varchar(10) = <b>N</b> / <i>I</i> 
                 w01_fontesite = varchar(40) = Fonte 
                 w01_wfontesite = varchar(10) = Larg 
                 w01_estilofontesite = varchar(10) = <b>N</b> / <i>I</i> 
                 w01_corfontesite = varchar(10) = Cor 
                 w01_fonteativo = varchar(40) = Fonte 
                 w01_tamfonteativo = varchar(10) = Tam 
                 w01_wfonteativo = varchar(10) = Larg 
                 w01_estilofonteativo = varchar(10) = <b>N</b> / <i>I</i> 
                 w01_corfonteativo = varchar(10) = Cor 
                 w01_fonteinput = varchar(40) = Fonte 
                 w01_tamfonteinput = varchar(10) = Tamanho 
                 w01_corfonteinput = varchar(10) = Cor da Fonte 
                 w01_corbordainput = varchar(10) = Cor da Borda 
                 w01_bordainput = varchar(10) = Borda 
                 w01_estiloinput = varchar(10) = Estilo 
                 w01_corfundoinput = varchar(10) = Cor de Fundo 
                 w01_linhafontemenu = varchar(10) = Linha 
                 w01_linhafontesite = varchar(10) = Linha 
                 w01_linhafonteativo = varchar(10) = Linha 
                 w01_estilofonteinput = varchar(10) = Estilo 
                 w01_fontebotao = varchar(40) = Fonte 
                 w01_tamfontebotao = varchar(10) = Tamanho 
                 w01_estilofontebotao = varchar(10) = Estilo 
                 w01_wfontebotao = varchar(10) = Largura 
                 w01_corfontebotao = varchar(10) = Cor da Fonte 
                 w01_corfundobotao = varchar(10) = Cor de Fundo 
                 w01_bordabotao = varchar(10) = Borda 
                 w01_estilobotao = varchar(10) = Estilo 
                 w01_corbordabotao = varchar(10) = Cor da Borda 
                 w01_titulo = varchar(150) = Título da Página 
                 w01_instit = int8 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_confsite() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("confsite"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->w01_cod = ($this->w01_cod == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_cod"]:$this->w01_cod);
       $this->w01_descricao = ($this->w01_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_descricao"]:$this->w01_descricao);
       $this->w01_corbody = ($this->w01_corbody == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corbody"]:$this->w01_corbody);
       $this->w01_cortexto = ($this->w01_cortexto == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_cortexto"]:$this->w01_cortexto);
       $this->w01_corbordamenu = ($this->w01_corbordamenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corbordamenu"]:$this->w01_corbordamenu);
       $this->w01_corfundomenu = ($this->w01_corfundomenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfundomenu"]:$this->w01_corfundomenu);
       $this->w01_corfundomenuativo = ($this->w01_corfundomenuativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfundomenuativo"]:$this->w01_corfundomenuativo);
       $this->w01_corfontemenu = ($this->w01_corfontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfontemenu"]:$this->w01_corfontemenu);
       $this->w01_tamfontesite = ($this->w01_tamfontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_tamfontesite"]:$this->w01_tamfontesite);
       $this->w01_bordamenu = ($this->w01_bordamenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_bordamenu"]:$this->w01_bordamenu);
       $this->w01_estilomenu = ($this->w01_estilomenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilomenu"]:$this->w01_estilomenu);
       $this->w01_fontemenu = ($this->w01_fontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_fontemenu"]:$this->w01_fontemenu);
       $this->w01_tamfontemenu = ($this->w01_tamfontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_tamfontemenu"]:$this->w01_tamfontemenu);
       $this->w01_wfontemenu = ($this->w01_wfontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_wfontemenu"]:$this->w01_wfontemenu);
       $this->w01_estilofontemenu = ($this->w01_estilofontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilofontemenu"]:$this->w01_estilofontemenu);
       $this->w01_fontesite = ($this->w01_fontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_fontesite"]:$this->w01_fontesite);
       $this->w01_wfontesite = ($this->w01_wfontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_wfontesite"]:$this->w01_wfontesite);
       $this->w01_estilofontesite = ($this->w01_estilofontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilofontesite"]:$this->w01_estilofontesite);
       $this->w01_corfontesite = ($this->w01_corfontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfontesite"]:$this->w01_corfontesite);
       $this->w01_fonteativo = ($this->w01_fonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_fonteativo"]:$this->w01_fonteativo);
       $this->w01_tamfonteativo = ($this->w01_tamfonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_tamfonteativo"]:$this->w01_tamfonteativo);
       $this->w01_wfonteativo = ($this->w01_wfonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_wfonteativo"]:$this->w01_wfonteativo);
       $this->w01_estilofonteativo = ($this->w01_estilofonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilofonteativo"]:$this->w01_estilofonteativo);
       $this->w01_corfonteativo = ($this->w01_corfonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfonteativo"]:$this->w01_corfonteativo);
       $this->w01_fonteinput = ($this->w01_fonteinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_fonteinput"]:$this->w01_fonteinput);
       $this->w01_tamfonteinput = ($this->w01_tamfonteinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_tamfonteinput"]:$this->w01_tamfonteinput);
       $this->w01_corfonteinput = ($this->w01_corfonteinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfonteinput"]:$this->w01_corfonteinput);
       $this->w01_corbordainput = ($this->w01_corbordainput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corbordainput"]:$this->w01_corbordainput);
       $this->w01_bordainput = ($this->w01_bordainput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_bordainput"]:$this->w01_bordainput);
       $this->w01_estiloinput = ($this->w01_estiloinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estiloinput"]:$this->w01_estiloinput);
       $this->w01_corfundoinput = ($this->w01_corfundoinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfundoinput"]:$this->w01_corfundoinput);
       $this->w01_linhafontemenu = ($this->w01_linhafontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_linhafontemenu"]:$this->w01_linhafontemenu);
       $this->w01_linhafontesite = ($this->w01_linhafontesite == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_linhafontesite"]:$this->w01_linhafontesite);
       $this->w01_linhafonteativo = ($this->w01_linhafonteativo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_linhafonteativo"]:$this->w01_linhafonteativo);
       $this->w01_estilofonteinput = ($this->w01_estilofonteinput == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilofonteinput"]:$this->w01_estilofonteinput);
       $this->w01_fontebotao = ($this->w01_fontebotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_fontebotao"]:$this->w01_fontebotao);
       $this->w01_tamfontebotao = ($this->w01_tamfontebotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_tamfontebotao"]:$this->w01_tamfontebotao);
       $this->w01_estilofontebotao = ($this->w01_estilofontebotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilofontebotao"]:$this->w01_estilofontebotao);
       $this->w01_wfontebotao = ($this->w01_wfontebotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_wfontebotao"]:$this->w01_wfontebotao);
       $this->w01_corfontebotao = ($this->w01_corfontebotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfontebotao"]:$this->w01_corfontebotao);
       $this->w01_corfundobotao = ($this->w01_corfundobotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corfundobotao"]:$this->w01_corfundobotao);
       $this->w01_bordabotao = ($this->w01_bordabotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_bordabotao"]:$this->w01_bordabotao);
       $this->w01_estilobotao = ($this->w01_estilobotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_estilobotao"]:$this->w01_estilobotao);
       $this->w01_corbordabotao = ($this->w01_corbordabotao == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_corbordabotao"]:$this->w01_corbordabotao);
       $this->w01_titulo = ($this->w01_titulo == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_titulo"]:$this->w01_titulo);
       $this->w01_instit = ($this->w01_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_instit"]:$this->w01_instit);
     }else{
       $this->w01_cod = ($this->w01_cod == ""?@$GLOBALS["HTTP_POST_VARS"]["w01_cod"]:$this->w01_cod);
     }
   }
   // funcao para inclusao
   function incluir ($w01_cod){ 
      $this->atualizacampos();
     if($this->w01_descricao == null ){ 
       $this->erro_sql = " Campo Nome do Site nao Informado.";
       $this->erro_campo = "w01_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corbody == null ){ 
       $this->erro_sql = " Campo Cor do Site nao Informado.";
       $this->erro_campo = "w01_corbody";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_cortexto == null ){ 
       $this->erro_sql = " Campo Cor do Texto nao Informado.";
       $this->erro_campo = "w01_cortexto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corbordamenu == null ){ 
       $this->erro_sql = " Campo Cor da Borda nao Informado.";
       $this->erro_campo = "w01_corbordamenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfundomenu == null ){ 
       $this->erro_sql = " Campo Cor de Fundo nao Informado.";
       $this->erro_campo = "w01_corfundomenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfundomenuativo == null ){ 
       $this->erro_sql = " Campo Cor de Fundo nao Informado.";
       $this->erro_campo = "w01_corfundomenuativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfontemenu == null ){ 
       $this->erro_sql = " Campo Cor nao Informado.";
       $this->erro_campo = "w01_corfontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_tamfontesite == null ){ 
       $this->erro_sql = " Campo Tam nao Informado.";
       $this->erro_campo = "w01_tamfontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_bordamenu == null ){ 
       $this->erro_sql = " Campo Bordas do Menu nao Informado.";
       $this->erro_campo = "w01_bordamenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilomenu == null ){ 
       $this->erro_sql = " Campo Estilo nao Informado.";
       $this->erro_campo = "w01_estilomenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_fontemenu == null ){ 
       $this->erro_sql = " Campo Fonte nao Informado.";
       $this->erro_campo = "w01_fontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_tamfontemenu == null ){ 
       $this->erro_sql = " Campo Tam nao Informado.";
       $this->erro_campo = "w01_tamfontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_wfontemenu == null ){ 
       $this->erro_sql = " Campo Larg nao Informado.";
       $this->erro_campo = "w01_wfontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilofontemenu == null ){ 
       $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
       $this->erro_campo = "w01_estilofontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_fontesite == null ){ 
       $this->erro_sql = " Campo Fonte nao Informado.";
       $this->erro_campo = "w01_fontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_wfontesite == null ){ 
       $this->erro_sql = " Campo Larg nao Informado.";
       $this->erro_campo = "w01_wfontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilofontesite == null ){ 
       $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
       $this->erro_campo = "w01_estilofontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfontesite == null ){ 
       $this->erro_sql = " Campo Cor nao Informado.";
       $this->erro_campo = "w01_corfontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_fonteativo == null ){ 
       $this->erro_sql = " Campo Fonte nao Informado.";
       $this->erro_campo = "w01_fonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_tamfonteativo == null ){ 
       $this->erro_sql = " Campo Tam nao Informado.";
       $this->erro_campo = "w01_tamfonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_wfonteativo == null ){ 
       $this->erro_sql = " Campo Larg nao Informado.";
       $this->erro_campo = "w01_wfonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilofonteativo == null ){ 
       $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
       $this->erro_campo = "w01_estilofonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfonteativo == null ){ 
       $this->erro_sql = " Campo Cor nao Informado.";
       $this->erro_campo = "w01_corfonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_fonteinput == null ){ 
       $this->erro_sql = " Campo Fonte nao Informado.";
       $this->erro_campo = "w01_fonteinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_tamfonteinput == null ){ 
       $this->erro_sql = " Campo Tamanho nao Informado.";
       $this->erro_campo = "w01_tamfonteinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfonteinput == null ){ 
       $this->erro_sql = " Campo Cor da Fonte nao Informado.";
       $this->erro_campo = "w01_corfonteinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corbordainput == null ){ 
       $this->erro_sql = " Campo Cor da Borda nao Informado.";
       $this->erro_campo = "w01_corbordainput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_bordainput == null ){ 
       $this->erro_sql = " Campo Borda nao Informado.";
       $this->erro_campo = "w01_bordainput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estiloinput == null ){ 
       $this->erro_sql = " Campo Estilo nao Informado.";
       $this->erro_campo = "w01_estiloinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfundoinput == null ){ 
       $this->erro_sql = " Campo Cor de Fundo nao Informado.";
       $this->erro_campo = "w01_corfundoinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_linhafontemenu == null ){ 
       $this->erro_sql = " Campo Linha nao Informado.";
       $this->erro_campo = "w01_linhafontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_linhafontesite == null ){ 
       $this->erro_sql = " Campo Linha nao Informado.";
       $this->erro_campo = "w01_linhafontesite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_linhafonteativo == null ){ 
       $this->erro_sql = " Campo Linha nao Informado.";
       $this->erro_campo = "w01_linhafonteativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilofonteinput == null ){ 
       $this->erro_sql = " Campo Estilo nao Informado.";
       $this->erro_campo = "w01_estilofonteinput";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_fontebotao == null ){ 
       $this->erro_sql = " Campo Fonte nao Informado.";
       $this->erro_campo = "w01_fontebotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_tamfontebotao == null ){ 
       $this->erro_sql = " Campo Tamanho nao Informado.";
       $this->erro_campo = "w01_tamfontebotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilofontebotao == null ){ 
       $this->erro_sql = " Campo Estilo nao Informado.";
       $this->erro_campo = "w01_estilofontebotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_wfontebotao == null ){ 
       $this->erro_sql = " Campo Largura nao Informado.";
       $this->erro_campo = "w01_wfontebotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfontebotao == null ){ 
       $this->erro_sql = " Campo Cor da Fonte nao Informado.";
       $this->erro_campo = "w01_corfontebotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corfundobotao == null ){ 
       $this->erro_sql = " Campo Cor de Fundo nao Informado.";
       $this->erro_campo = "w01_corfundobotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_bordabotao == null ){ 
       $this->erro_sql = " Campo Borda nao Informado.";
       $this->erro_campo = "w01_bordabotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_estilobotao == null ){ 
       $this->erro_sql = " Campo Estilo nao Informado.";
       $this->erro_campo = "w01_estilobotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_corbordabotao == null ){ 
       $this->erro_sql = " Campo Cor da Borda nao Informado.";
       $this->erro_campo = "w01_corbordabotao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w01_titulo == null ){ 
       $this->w01_titulo = "DBSeller Informática Ltda";
     }
     if($this->w01_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "w01_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w01_cod = $w01_cod; 
     if(($this->w01_cod == null) || ($this->w01_cod == "") ){ 
       $this->erro_sql = " Campo w01_cod nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into confsite(
                                       w01_cod 
                                      ,w01_descricao 
                                      ,w01_corbody 
                                      ,w01_cortexto 
                                      ,w01_corbordamenu 
                                      ,w01_corfundomenu 
                                      ,w01_corfundomenuativo 
                                      ,w01_corfontemenu 
                                      ,w01_tamfontesite 
                                      ,w01_bordamenu 
                                      ,w01_estilomenu 
                                      ,w01_fontemenu 
                                      ,w01_tamfontemenu 
                                      ,w01_wfontemenu 
                                      ,w01_estilofontemenu 
                                      ,w01_fontesite 
                                      ,w01_wfontesite 
                                      ,w01_estilofontesite 
                                      ,w01_corfontesite 
                                      ,w01_fonteativo 
                                      ,w01_tamfonteativo 
                                      ,w01_wfonteativo 
                                      ,w01_estilofonteativo 
                                      ,w01_corfonteativo 
                                      ,w01_fonteinput 
                                      ,w01_tamfonteinput 
                                      ,w01_corfonteinput 
                                      ,w01_corbordainput 
                                      ,w01_bordainput 
                                      ,w01_estiloinput 
                                      ,w01_corfundoinput 
                                      ,w01_linhafontemenu 
                                      ,w01_linhafontesite 
                                      ,w01_linhafonteativo 
                                      ,w01_estilofonteinput 
                                      ,w01_fontebotao 
                                      ,w01_tamfontebotao 
                                      ,w01_estilofontebotao 
                                      ,w01_wfontebotao 
                                      ,w01_corfontebotao 
                                      ,w01_corfundobotao 
                                      ,w01_bordabotao 
                                      ,w01_estilobotao 
                                      ,w01_corbordabotao 
                                      ,w01_titulo 
                                      ,w01_instit 
                       )
                values (
                                $this->w01_cod 
                               ,'$this->w01_descricao' 
                               ,'$this->w01_corbody' 
                               ,'$this->w01_cortexto' 
                               ,'$this->w01_corbordamenu' 
                               ,'$this->w01_corfundomenu' 
                               ,'$this->w01_corfundomenuativo' 
                               ,'$this->w01_corfontemenu' 
                               ,'$this->w01_tamfontesite' 
                               ,'$this->w01_bordamenu' 
                               ,'$this->w01_estilomenu' 
                               ,'$this->w01_fontemenu' 
                               ,'$this->w01_tamfontemenu' 
                               ,'$this->w01_wfontemenu' 
                               ,'$this->w01_estilofontemenu' 
                               ,'$this->w01_fontesite' 
                               ,'$this->w01_wfontesite' 
                               ,'$this->w01_estilofontesite' 
                               ,'$this->w01_corfontesite' 
                               ,'$this->w01_fonteativo' 
                               ,'$this->w01_tamfonteativo' 
                               ,'$this->w01_wfonteativo' 
                               ,'$this->w01_estilofonteativo' 
                               ,'$this->w01_corfonteativo' 
                               ,'$this->w01_fonteinput' 
                               ,'$this->w01_tamfonteinput' 
                               ,'$this->w01_corfonteinput' 
                               ,'$this->w01_corbordainput' 
                               ,'$this->w01_bordainput' 
                               ,'$this->w01_estiloinput' 
                               ,'$this->w01_corfundoinput' 
                               ,'$this->w01_linhafontemenu' 
                               ,'$this->w01_linhafontesite' 
                               ,'$this->w01_linhafonteativo' 
                               ,'$this->w01_estilofonteinput' 
                               ,'$this->w01_fontebotao' 
                               ,'$this->w01_tamfontebotao' 
                               ,'$this->w01_estilofontebotao' 
                               ,'$this->w01_wfontebotao' 
                               ,'$this->w01_corfontebotao' 
                               ,'$this->w01_corfundobotao' 
                               ,'$this->w01_bordabotao' 
                               ,'$this->w01_estilobotao' 
                               ,'$this->w01_corbordabotao' 
                               ,'$this->w01_titulo' 
                               ,$this->w01_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração do site ($this->w01_cod) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração do site já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração do site ($this->w01_cod) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w01_cod;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w01_cod));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3416,'$this->w01_cod','I')");
       $resac = db_query("insert into db_acount values($acount,423,3416,'','".AddSlashes(pg_result($resaco,0,'w01_cod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3570,'','".AddSlashes(pg_result($resaco,0,'w01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3417,'','".AddSlashes(pg_result($resaco,0,'w01_corbody'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3418,'','".AddSlashes(pg_result($resaco,0,'w01_cortexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3421,'','".AddSlashes(pg_result($resaco,0,'w01_corbordamenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3422,'','".AddSlashes(pg_result($resaco,0,'w01_corfundomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3423,'','".AddSlashes(pg_result($resaco,0,'w01_corfundomenuativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3428,'','".AddSlashes(pg_result($resaco,0,'w01_corfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3430,'','".AddSlashes(pg_result($resaco,0,'w01_tamfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3419,'','".AddSlashes(pg_result($resaco,0,'w01_bordamenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3420,'','".AddSlashes(pg_result($resaco,0,'w01_estilomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3424,'','".AddSlashes(pg_result($resaco,0,'w01_fontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3425,'','".AddSlashes(pg_result($resaco,0,'w01_tamfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3426,'','".AddSlashes(pg_result($resaco,0,'w01_wfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3427,'','".AddSlashes(pg_result($resaco,0,'w01_estilofontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3429,'','".AddSlashes(pg_result($resaco,0,'w01_fontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3431,'','".AddSlashes(pg_result($resaco,0,'w01_wfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3432,'','".AddSlashes(pg_result($resaco,0,'w01_estilofontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3433,'','".AddSlashes(pg_result($resaco,0,'w01_corfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3434,'','".AddSlashes(pg_result($resaco,0,'w01_fonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3435,'','".AddSlashes(pg_result($resaco,0,'w01_tamfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3436,'','".AddSlashes(pg_result($resaco,0,'w01_wfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3437,'','".AddSlashes(pg_result($resaco,0,'w01_estilofonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3446,'','".AddSlashes(pg_result($resaco,0,'w01_corfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3438,'','".AddSlashes(pg_result($resaco,0,'w01_fonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3439,'','".AddSlashes(pg_result($resaco,0,'w01_tamfonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3440,'','".AddSlashes(pg_result($resaco,0,'w01_corfonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3441,'','".AddSlashes(pg_result($resaco,0,'w01_corbordainput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3442,'','".AddSlashes(pg_result($resaco,0,'w01_bordainput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3443,'','".AddSlashes(pg_result($resaco,0,'w01_estiloinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3444,'','".AddSlashes(pg_result($resaco,0,'w01_corfundoinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3556,'','".AddSlashes(pg_result($resaco,0,'w01_linhafontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3557,'','".AddSlashes(pg_result($resaco,0,'w01_linhafontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3558,'','".AddSlashes(pg_result($resaco,0,'w01_linhafonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3559,'','".AddSlashes(pg_result($resaco,0,'w01_estilofonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3571,'','".AddSlashes(pg_result($resaco,0,'w01_fontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3561,'','".AddSlashes(pg_result($resaco,0,'w01_tamfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3560,'','".AddSlashes(pg_result($resaco,0,'w01_estilofontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3564,'','".AddSlashes(pg_result($resaco,0,'w01_wfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3565,'','".AddSlashes(pg_result($resaco,0,'w01_corfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3566,'','".AddSlashes(pg_result($resaco,0,'w01_corfundobotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3567,'','".AddSlashes(pg_result($resaco,0,'w01_bordabotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3568,'','".AddSlashes(pg_result($resaco,0,'w01_estilobotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,3569,'','".AddSlashes(pg_result($resaco,0,'w01_corbordabotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,5059,'','".AddSlashes(pg_result($resaco,0,'w01_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,423,11005,'','".AddSlashes(pg_result($resaco,0,'w01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w01_cod=null) { 
      $this->atualizacampos();
     $sql = " update confsite set ";
     $virgula = "";
     if(trim($this->w01_cod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_cod"])){ 
       $sql  .= $virgula." w01_cod = $this->w01_cod ";
       $virgula = ",";
       if(trim($this->w01_cod) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "w01_cod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_descricao"])){ 
       $sql  .= $virgula." w01_descricao = '$this->w01_descricao' ";
       $virgula = ",";
       if(trim($this->w01_descricao) == null ){ 
         $this->erro_sql = " Campo Nome do Site nao Informado.";
         $this->erro_campo = "w01_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corbody)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corbody"])){ 
       $sql  .= $virgula." w01_corbody = '$this->w01_corbody' ";
       $virgula = ",";
       if(trim($this->w01_corbody) == null ){ 
         $this->erro_sql = " Campo Cor do Site nao Informado.";
         $this->erro_campo = "w01_corbody";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_cortexto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_cortexto"])){ 
       $sql  .= $virgula." w01_cortexto = '$this->w01_cortexto' ";
       $virgula = ",";
       if(trim($this->w01_cortexto) == null ){ 
         $this->erro_sql = " Campo Cor do Texto nao Informado.";
         $this->erro_campo = "w01_cortexto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corbordamenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordamenu"])){ 
       $sql  .= $virgula." w01_corbordamenu = '$this->w01_corbordamenu' ";
       $virgula = ",";
       if(trim($this->w01_corbordamenu) == null ){ 
         $this->erro_sql = " Campo Cor da Borda nao Informado.";
         $this->erro_campo = "w01_corbordamenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfundomenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundomenu"])){ 
       $sql  .= $virgula." w01_corfundomenu = '$this->w01_corfundomenu' ";
       $virgula = ",";
       if(trim($this->w01_corfundomenu) == null ){ 
         $this->erro_sql = " Campo Cor de Fundo nao Informado.";
         $this->erro_campo = "w01_corfundomenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfundomenuativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundomenuativo"])){ 
       $sql  .= $virgula." w01_corfundomenuativo = '$this->w01_corfundomenuativo' ";
       $virgula = ",";
       if(trim($this->w01_corfundomenuativo) == null ){ 
         $this->erro_sql = " Campo Cor de Fundo nao Informado.";
         $this->erro_campo = "w01_corfundomenuativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontemenu"])){ 
       $sql  .= $virgula." w01_corfontemenu = '$this->w01_corfontemenu' ";
       $virgula = ",";
       if(trim($this->w01_corfontemenu) == null ){ 
         $this->erro_sql = " Campo Cor nao Informado.";
         $this->erro_campo = "w01_corfontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_tamfontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontesite"])){ 
       $sql  .= $virgula." w01_tamfontesite = '$this->w01_tamfontesite' ";
       $virgula = ",";
       if(trim($this->w01_tamfontesite) == null ){ 
         $this->erro_sql = " Campo Tam nao Informado.";
         $this->erro_campo = "w01_tamfontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_bordamenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_bordamenu"])){ 
       $sql  .= $virgula." w01_bordamenu = '$this->w01_bordamenu' ";
       $virgula = ",";
       if(trim($this->w01_bordamenu) == null ){ 
         $this->erro_sql = " Campo Bordas do Menu nao Informado.";
         $this->erro_campo = "w01_bordamenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilomenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilomenu"])){ 
       $sql  .= $virgula." w01_estilomenu = '$this->w01_estilomenu' ";
       $virgula = ",";
       if(trim($this->w01_estilomenu) == null ){ 
         $this->erro_sql = " Campo Estilo nao Informado.";
         $this->erro_campo = "w01_estilomenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_fontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_fontemenu"])){ 
       $sql  .= $virgula." w01_fontemenu = '$this->w01_fontemenu' ";
       $virgula = ",";
       if(trim($this->w01_fontemenu) == null ){ 
         $this->erro_sql = " Campo Fonte nao Informado.";
         $this->erro_campo = "w01_fontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_tamfontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontemenu"])){ 
       $sql  .= $virgula." w01_tamfontemenu = '$this->w01_tamfontemenu' ";
       $virgula = ",";
       if(trim($this->w01_tamfontemenu) == null ){ 
         $this->erro_sql = " Campo Tam nao Informado.";
         $this->erro_campo = "w01_tamfontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_wfontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontemenu"])){ 
       $sql  .= $virgula." w01_wfontemenu = '$this->w01_wfontemenu' ";
       $virgula = ",";
       if(trim($this->w01_wfontemenu) == null ){ 
         $this->erro_sql = " Campo Larg nao Informado.";
         $this->erro_campo = "w01_wfontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilofontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontemenu"])){ 
       $sql  .= $virgula." w01_estilofontemenu = '$this->w01_estilofontemenu' ";
       $virgula = ",";
       if(trim($this->w01_estilofontemenu) == null ){ 
         $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
         $this->erro_campo = "w01_estilofontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_fontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_fontesite"])){ 
       $sql  .= $virgula." w01_fontesite = '$this->w01_fontesite' ";
       $virgula = ",";
       if(trim($this->w01_fontesite) == null ){ 
         $this->erro_sql = " Campo Fonte nao Informado.";
         $this->erro_campo = "w01_fontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_wfontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontesite"])){ 
       $sql  .= $virgula." w01_wfontesite = '$this->w01_wfontesite' ";
       $virgula = ",";
       if(trim($this->w01_wfontesite) == null ){ 
         $this->erro_sql = " Campo Larg nao Informado.";
         $this->erro_campo = "w01_wfontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilofontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontesite"])){ 
       $sql  .= $virgula." w01_estilofontesite = '$this->w01_estilofontesite' ";
       $virgula = ",";
       if(trim($this->w01_estilofontesite) == null ){ 
         $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
         $this->erro_campo = "w01_estilofontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontesite"])){ 
       $sql  .= $virgula." w01_corfontesite = '$this->w01_corfontesite' ";
       $virgula = ",";
       if(trim($this->w01_corfontesite) == null ){ 
         $this->erro_sql = " Campo Cor nao Informado.";
         $this->erro_campo = "w01_corfontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_fonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_fonteativo"])){ 
       $sql  .= $virgula." w01_fonteativo = '$this->w01_fonteativo' ";
       $virgula = ",";
       if(trim($this->w01_fonteativo) == null ){ 
         $this->erro_sql = " Campo Fonte nao Informado.";
         $this->erro_campo = "w01_fonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_tamfonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfonteativo"])){ 
       $sql  .= $virgula." w01_tamfonteativo = '$this->w01_tamfonteativo' ";
       $virgula = ",";
       if(trim($this->w01_tamfonteativo) == null ){ 
         $this->erro_sql = " Campo Tam nao Informado.";
         $this->erro_campo = "w01_tamfonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_wfonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_wfonteativo"])){ 
       $sql  .= $virgula." w01_wfonteativo = '$this->w01_wfonteativo' ";
       $virgula = ",";
       if(trim($this->w01_wfonteativo) == null ){ 
         $this->erro_sql = " Campo Larg nao Informado.";
         $this->erro_campo = "w01_wfonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilofonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofonteativo"])){ 
       $sql  .= $virgula." w01_estilofonteativo = '$this->w01_estilofonteativo' ";
       $virgula = ",";
       if(trim($this->w01_estilofonteativo) == null ){ 
         $this->erro_sql = " Campo <b>N</b> / <i>I</i> nao Informado.";
         $this->erro_campo = "w01_estilofonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfonteativo"])){ 
       $sql  .= $virgula." w01_corfonteativo = '$this->w01_corfonteativo' ";
       $virgula = ",";
       if(trim($this->w01_corfonteativo) == null ){ 
         $this->erro_sql = " Campo Cor nao Informado.";
         $this->erro_campo = "w01_corfonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_fonteinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_fonteinput"])){ 
       $sql  .= $virgula." w01_fonteinput = '$this->w01_fonteinput' ";
       $virgula = ",";
       if(trim($this->w01_fonteinput) == null ){ 
         $this->erro_sql = " Campo Fonte nao Informado.";
         $this->erro_campo = "w01_fonteinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_tamfonteinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfonteinput"])){ 
       $sql  .= $virgula." w01_tamfonteinput = '$this->w01_tamfonteinput' ";
       $virgula = ",";
       if(trim($this->w01_tamfonteinput) == null ){ 
         $this->erro_sql = " Campo Tamanho nao Informado.";
         $this->erro_campo = "w01_tamfonteinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfonteinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfonteinput"])){ 
       $sql  .= $virgula." w01_corfonteinput = '$this->w01_corfonteinput' ";
       $virgula = ",";
       if(trim($this->w01_corfonteinput) == null ){ 
         $this->erro_sql = " Campo Cor da Fonte nao Informado.";
         $this->erro_campo = "w01_corfonteinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corbordainput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordainput"])){ 
       $sql  .= $virgula." w01_corbordainput = '$this->w01_corbordainput' ";
       $virgula = ",";
       if(trim($this->w01_corbordainput) == null ){ 
         $this->erro_sql = " Campo Cor da Borda nao Informado.";
         $this->erro_campo = "w01_corbordainput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_bordainput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_bordainput"])){ 
       $sql  .= $virgula." w01_bordainput = '$this->w01_bordainput' ";
       $virgula = ",";
       if(trim($this->w01_bordainput) == null ){ 
         $this->erro_sql = " Campo Borda nao Informado.";
         $this->erro_campo = "w01_bordainput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estiloinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estiloinput"])){ 
       $sql  .= $virgula." w01_estiloinput = '$this->w01_estiloinput' ";
       $virgula = ",";
       if(trim($this->w01_estiloinput) == null ){ 
         $this->erro_sql = " Campo Estilo nao Informado.";
         $this->erro_campo = "w01_estiloinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfundoinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundoinput"])){ 
       $sql  .= $virgula." w01_corfundoinput = '$this->w01_corfundoinput' ";
       $virgula = ",";
       if(trim($this->w01_corfundoinput) == null ){ 
         $this->erro_sql = " Campo Cor de Fundo nao Informado.";
         $this->erro_campo = "w01_corfundoinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_linhafontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafontemenu"])){ 
       $sql  .= $virgula." w01_linhafontemenu = '$this->w01_linhafontemenu' ";
       $virgula = ",";
       if(trim($this->w01_linhafontemenu) == null ){ 
         $this->erro_sql = " Campo Linha nao Informado.";
         $this->erro_campo = "w01_linhafontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_linhafontesite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafontesite"])){ 
       $sql  .= $virgula." w01_linhafontesite = '$this->w01_linhafontesite' ";
       $virgula = ",";
       if(trim($this->w01_linhafontesite) == null ){ 
         $this->erro_sql = " Campo Linha nao Informado.";
         $this->erro_campo = "w01_linhafontesite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_linhafonteativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafonteativo"])){ 
       $sql  .= $virgula." w01_linhafonteativo = '$this->w01_linhafonteativo' ";
       $virgula = ",";
       if(trim($this->w01_linhafonteativo) == null ){ 
         $this->erro_sql = " Campo Linha nao Informado.";
         $this->erro_campo = "w01_linhafonteativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilofonteinput)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofonteinput"])){ 
       $sql  .= $virgula." w01_estilofonteinput = '$this->w01_estilofonteinput' ";
       $virgula = ",";
       if(trim($this->w01_estilofonteinput) == null ){ 
         $this->erro_sql = " Campo Estilo nao Informado.";
         $this->erro_campo = "w01_estilofonteinput";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_fontebotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_fontebotao"])){ 
       $sql  .= $virgula." w01_fontebotao = '$this->w01_fontebotao' ";
       $virgula = ",";
       if(trim($this->w01_fontebotao) == null ){ 
         $this->erro_sql = " Campo Fonte nao Informado.";
         $this->erro_campo = "w01_fontebotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_tamfontebotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontebotao"])){ 
       $sql  .= $virgula." w01_tamfontebotao = '$this->w01_tamfontebotao' ";
       $virgula = ",";
       if(trim($this->w01_tamfontebotao) == null ){ 
         $this->erro_sql = " Campo Tamanho nao Informado.";
         $this->erro_campo = "w01_tamfontebotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilofontebotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontebotao"])){ 
       $sql  .= $virgula." w01_estilofontebotao = '$this->w01_estilofontebotao' ";
       $virgula = ",";
       if(trim($this->w01_estilofontebotao) == null ){ 
         $this->erro_sql = " Campo Estilo nao Informado.";
         $this->erro_campo = "w01_estilofontebotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_wfontebotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontebotao"])){ 
       $sql  .= $virgula." w01_wfontebotao = '$this->w01_wfontebotao' ";
       $virgula = ",";
       if(trim($this->w01_wfontebotao) == null ){ 
         $this->erro_sql = " Campo Largura nao Informado.";
         $this->erro_campo = "w01_wfontebotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfontebotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontebotao"])){ 
       $sql  .= $virgula." w01_corfontebotao = '$this->w01_corfontebotao' ";
       $virgula = ",";
       if(trim($this->w01_corfontebotao) == null ){ 
         $this->erro_sql = " Campo Cor da Fonte nao Informado.";
         $this->erro_campo = "w01_corfontebotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corfundobotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundobotao"])){ 
       $sql  .= $virgula." w01_corfundobotao = '$this->w01_corfundobotao' ";
       $virgula = ",";
       if(trim($this->w01_corfundobotao) == null ){ 
         $this->erro_sql = " Campo Cor de Fundo nao Informado.";
         $this->erro_campo = "w01_corfundobotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_bordabotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_bordabotao"])){ 
       $sql  .= $virgula." w01_bordabotao = '$this->w01_bordabotao' ";
       $virgula = ",";
       if(trim($this->w01_bordabotao) == null ){ 
         $this->erro_sql = " Campo Borda nao Informado.";
         $this->erro_campo = "w01_bordabotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_estilobotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_estilobotao"])){ 
       $sql  .= $virgula." w01_estilobotao = '$this->w01_estilobotao' ";
       $virgula = ",";
       if(trim($this->w01_estilobotao) == null ){ 
         $this->erro_sql = " Campo Estilo nao Informado.";
         $this->erro_campo = "w01_estilobotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_corbordabotao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordabotao"])){ 
       $sql  .= $virgula." w01_corbordabotao = '$this->w01_corbordabotao' ";
       $virgula = ",";
       if(trim($this->w01_corbordabotao) == null ){ 
         $this->erro_sql = " Campo Cor da Borda nao Informado.";
         $this->erro_campo = "w01_corbordabotao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w01_titulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_titulo"])){ 
       $sql  .= $virgula." w01_titulo = '$this->w01_titulo' ";
       $virgula = ",";
     }
     if(trim($this->w01_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w01_instit"])){ 
       $sql  .= $virgula." w01_instit = $this->w01_instit ";
       $virgula = ",";
       if(trim($this->w01_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "w01_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w01_cod!=null){
       $sql .= " w01_cod = $this->w01_cod";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w01_cod));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3416,'$this->w01_cod','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_cod"]))
           $resac = db_query("insert into db_acount values($acount,423,3416,'".AddSlashes(pg_result($resaco,$conresaco,'w01_cod'))."','$this->w01_cod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_descricao"]))
           $resac = db_query("insert into db_acount values($acount,423,3570,'".AddSlashes(pg_result($resaco,$conresaco,'w01_descricao'))."','$this->w01_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corbody"]))
           $resac = db_query("insert into db_acount values($acount,423,3417,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corbody'))."','$this->w01_corbody',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_cortexto"]))
           $resac = db_query("insert into db_acount values($acount,423,3418,'".AddSlashes(pg_result($resaco,$conresaco,'w01_cortexto'))."','$this->w01_cortexto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordamenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3421,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corbordamenu'))."','$this->w01_corbordamenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundomenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3422,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfundomenu'))."','$this->w01_corfundomenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundomenuativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3423,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfundomenuativo'))."','$this->w01_corfundomenuativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3428,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfontemenu'))."','$this->w01_corfontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3430,'".AddSlashes(pg_result($resaco,$conresaco,'w01_tamfontesite'))."','$this->w01_tamfontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_bordamenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3419,'".AddSlashes(pg_result($resaco,$conresaco,'w01_bordamenu'))."','$this->w01_bordamenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilomenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3420,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilomenu'))."','$this->w01_estilomenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_fontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3424,'".AddSlashes(pg_result($resaco,$conresaco,'w01_fontemenu'))."','$this->w01_fontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3425,'".AddSlashes(pg_result($resaco,$conresaco,'w01_tamfontemenu'))."','$this->w01_tamfontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3426,'".AddSlashes(pg_result($resaco,$conresaco,'w01_wfontemenu'))."','$this->w01_wfontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3427,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilofontemenu'))."','$this->w01_estilofontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_fontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3429,'".AddSlashes(pg_result($resaco,$conresaco,'w01_fontesite'))."','$this->w01_fontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3431,'".AddSlashes(pg_result($resaco,$conresaco,'w01_wfontesite'))."','$this->w01_wfontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3432,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilofontesite'))."','$this->w01_estilofontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3433,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfontesite'))."','$this->w01_corfontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_fonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3434,'".AddSlashes(pg_result($resaco,$conresaco,'w01_fonteativo'))."','$this->w01_fonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3435,'".AddSlashes(pg_result($resaco,$conresaco,'w01_tamfonteativo'))."','$this->w01_tamfonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_wfonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3436,'".AddSlashes(pg_result($resaco,$conresaco,'w01_wfonteativo'))."','$this->w01_wfonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3437,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilofonteativo'))."','$this->w01_estilofonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3446,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfonteativo'))."','$this->w01_corfonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_fonteinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3438,'".AddSlashes(pg_result($resaco,$conresaco,'w01_fonteinput'))."','$this->w01_fonteinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfonteinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3439,'".AddSlashes(pg_result($resaco,$conresaco,'w01_tamfonteinput'))."','$this->w01_tamfonteinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfonteinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3440,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfonteinput'))."','$this->w01_corfonteinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordainput"]))
           $resac = db_query("insert into db_acount values($acount,423,3441,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corbordainput'))."','$this->w01_corbordainput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_bordainput"]))
           $resac = db_query("insert into db_acount values($acount,423,3442,'".AddSlashes(pg_result($resaco,$conresaco,'w01_bordainput'))."','$this->w01_bordainput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estiloinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3443,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estiloinput'))."','$this->w01_estiloinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundoinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3444,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfundoinput'))."','$this->w01_corfundoinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafontemenu"]))
           $resac = db_query("insert into db_acount values($acount,423,3556,'".AddSlashes(pg_result($resaco,$conresaco,'w01_linhafontemenu'))."','$this->w01_linhafontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafontesite"]))
           $resac = db_query("insert into db_acount values($acount,423,3557,'".AddSlashes(pg_result($resaco,$conresaco,'w01_linhafontesite'))."','$this->w01_linhafontesite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_linhafonteativo"]))
           $resac = db_query("insert into db_acount values($acount,423,3558,'".AddSlashes(pg_result($resaco,$conresaco,'w01_linhafonteativo'))."','$this->w01_linhafonteativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofonteinput"]))
           $resac = db_query("insert into db_acount values($acount,423,3559,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilofonteinput'))."','$this->w01_estilofonteinput',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_fontebotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3571,'".AddSlashes(pg_result($resaco,$conresaco,'w01_fontebotao'))."','$this->w01_fontebotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_tamfontebotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3561,'".AddSlashes(pg_result($resaco,$conresaco,'w01_tamfontebotao'))."','$this->w01_tamfontebotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilofontebotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3560,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilofontebotao'))."','$this->w01_estilofontebotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_wfontebotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3564,'".AddSlashes(pg_result($resaco,$conresaco,'w01_wfontebotao'))."','$this->w01_wfontebotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfontebotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3565,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfontebotao'))."','$this->w01_corfontebotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corfundobotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3566,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corfundobotao'))."','$this->w01_corfundobotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_bordabotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3567,'".AddSlashes(pg_result($resaco,$conresaco,'w01_bordabotao'))."','$this->w01_bordabotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_estilobotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3568,'".AddSlashes(pg_result($resaco,$conresaco,'w01_estilobotao'))."','$this->w01_estilobotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_corbordabotao"]))
           $resac = db_query("insert into db_acount values($acount,423,3569,'".AddSlashes(pg_result($resaco,$conresaco,'w01_corbordabotao'))."','$this->w01_corbordabotao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_titulo"]))
           $resac = db_query("insert into db_acount values($acount,423,5059,'".AddSlashes(pg_result($resaco,$conresaco,'w01_titulo'))."','$this->w01_titulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w01_instit"]))
           $resac = db_query("insert into db_acount values($acount,423,11005,'".AddSlashes(pg_result($resaco,$conresaco,'w01_instit'))."','$this->w01_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração do site nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w01_cod;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração do site nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w01_cod;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w01_cod;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w01_cod=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w01_cod));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3416,'$w01_cod','E')");
         $resac = db_query("insert into db_acount values($acount,423,3416,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_cod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3570,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3417,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corbody'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3418,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_cortexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3421,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corbordamenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3422,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfundomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3423,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfundomenuativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3428,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3430,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_tamfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3419,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_bordamenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3420,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3424,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_fontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3425,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_tamfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3426,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_wfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3427,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilofontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3429,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_fontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3431,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_wfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3432,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilofontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3433,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3434,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_fonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3435,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_tamfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3436,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_wfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3437,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilofonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3446,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3438,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_fonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3439,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_tamfonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3440,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3441,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corbordainput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3442,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_bordainput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3443,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estiloinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3444,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfundoinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3556,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_linhafontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3557,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_linhafontesite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3558,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_linhafonteativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3559,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilofonteinput'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3571,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_fontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3561,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_tamfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3560,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilofontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3564,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_wfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3565,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfontebotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3566,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corfundobotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3567,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_bordabotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3568,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_estilobotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,3569,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_corbordabotao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,5059,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,423,11005,'','".AddSlashes(pg_result($resaco,$iresaco,'w01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from confsite
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w01_cod != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w01_cod = $w01_cod ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração do site nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w01_cod;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração do site nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w01_cod;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w01_cod;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:confsite";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $w01_cod=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from confsite ";
     $sql .= "      inner join db_config  on  db_config.codigo = confsite.w01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($w01_cod!=null ){
         $sql2 .= " where confsite.w01_cod = $w01_cod "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $w01_cod=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from confsite ";
     $sql2 = "";
     if($dbwhere==""){
       if($w01_cod!=null ){
         $sql2 .= " where confsite.w01_cod = $w01_cod "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>