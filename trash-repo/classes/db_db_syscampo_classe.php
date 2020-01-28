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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_syscampo
class cl_db_syscampo { 
   // cria variaveis de erro 
   var $rotulocl     = null; 
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
   var $codcam = 0; 
   var $nomecam = null; 
   var $conteudo = null; 
   var $descricao = null; 
   var $valorinicial = null; 
   var $rotulo = null; 
   var $tamanho = 0; 
   var $nulo = 'f'; 
   var $maiusculo = 'f'; 
   var $autocompl = 'f'; 
   var $aceitatipo = 0; 
   var $tipoobj = null; 
   var $rotulorel = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codcam = int4 = Código 
                 nomecam = char(40) = Nome 
                 conteudo = char(40) = Tipo Campo 
                 descricao = text = Descrição 
                 valorinicial = varchar(100) = Valor Inicial 
                 rotulo = varchar(50) = Rótulo 
                 tamanho = int4 = Tamanho 
                 nulo = bool = Aceita Nulo 
                 maiusculo = bool = Maiúsculo 
                 autocompl = bool = Auto-completar 
                 aceitatipo = int4 = Valida 
                 tipoobj = varchar(20) = Obj. Formulário 
                 rotulorel = varchar(40) = Rótulo relatório 
                 ";
   //funcao construtor da classe 
   function cl_db_syscampo() { 
     //classes dos rotulos dos campos
     $this->rotulocl = new rotulo("db_syscampo"); 
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
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->nomecam = ($this->nomecam == ""?@$GLOBALS["HTTP_POST_VARS"]["nomecam"]:$this->nomecam);
       $this->conteudo = ($this->conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["conteudo"]:$this->conteudo);
       $this->descricao = ($this->descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["descricao"]:$this->descricao);
       $this->valorinicial = ($this->valorinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["valorinicial"]:$this->valorinicial);
       $this->rotulo = ($this->rotulo == ""?@$GLOBALS["HTTP_POST_VARS"]["rotulo"]:$this->rotulo);
       $this->tamanho = ($this->tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["tamanho"]:$this->tamanho);
       $this->nulo = ($this->nulo == "f"?@$GLOBALS["HTTP_POST_VARS"]["nulo"]:$this->nulo);
       $this->maiusculo = ($this->maiusculo == "f"?@$GLOBALS["HTTP_POST_VARS"]["maiusculo"]:$this->maiusculo);
       $this->autocompl = ($this->autocompl == "f"?@$GLOBALS["HTTP_POST_VARS"]["autocompl"]:$this->autocompl);
       $this->aceitatipo = ($this->aceitatipo == ""?@$GLOBALS["HTTP_POST_VARS"]["aceitatipo"]:$this->aceitatipo);
       $this->tipoobj = ($this->tipoobj == ""?@$GLOBALS["HTTP_POST_VARS"]["tipoobj"]:$this->tipoobj);
       $this->rotulorel = ($this->rotulorel == ""?@$GLOBALS["HTTP_POST_VARS"]["rotulorel"]:$this->rotulorel);
     }else{
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
     }
   }
   // funcao para inclusao
   function incluir ($codcam){ 
      $this->atualizacampos();
     if($this->nomecam == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "nomecam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->conteudo == null ){ 
       $this->erro_sql = " Campo Tipo Campo nao Informado.";
       $this->erro_campo = "conteudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rotulo == null ){ 
       $this->erro_sql = " Campo Rótulo nao Informado.";
       $this->erro_campo = "rotulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tamanho == null ){ 
       $this->tamanho = "0";
     }
     if($this->nulo == null ){ 
       $this->erro_sql = " Campo Aceita Nulo nao Informado.";
       $this->erro_campo = "nulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->maiusculo == null ){ 
       $this->erro_sql = " Campo Maiúsculo nao Informado.";
       $this->erro_campo = "maiusculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->autocompl == null ){ 
       $this->erro_sql = " Campo Auto-completar nao Informado.";
       $this->erro_campo = "autocompl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->aceitatipo == null ){ 
       $this->erro_sql = " Campo Valida nao Informado.";
       $this->erro_campo = "aceitatipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tipoobj == null ){ 
       $this->erro_sql = " Campo Obj. Formulário nao Informado.";
       $this->erro_campo = "tipoobj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rotulorel == null ){ 
       $this->erro_sql = " Campo Rótulo relatório nao Informado.";
       $this->erro_campo = "rotulorel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codcam == "" || $codcam == null ){
       $result = db_query("select nextval('db_syscampo_codcam_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_syscampo_codcam_seq do campo: codcam"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codcam = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_syscampo_codcam_seq");
       if(($result != false) && (pg_result($result,0,0) < $codcam)){
         $this->erro_sql = " Campo codcam maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codcam = $codcam; 
       }
     }
     if(($this->codcam == null) || ($this->codcam == "") ){ 
       $this->erro_sql = " Campo codcam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_syscampo(
                                       codcam 
                                      ,nomecam 
                                      ,conteudo 
                                      ,descricao 
                                      ,valorinicial 
                                      ,rotulo 
                                      ,tamanho 
                                      ,nulo 
                                      ,maiusculo 
                                      ,autocompl 
                                      ,aceitatipo 
                                      ,tipoobj 
                                      ,rotulorel 
                       )
                values (
                                $this->codcam 
                               ,'$this->nomecam' 
                               ,'$this->conteudo' 
                               ,'$this->descricao' 
                               ,'$this->valorinicial' 
                               ,'$this->rotulo' 
                               ,$this->tamanho 
                               ,'$this->nulo' 
                               ,'$this->maiusculo' 
                               ,'$this->autocompl' 
                               ,$this->aceitatipo 
                               ,'$this->tipoobj' 
                               ,'$this->rotulorel' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Campos das tabelas ($this->codcam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Campos das tabelas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Campos das tabelas ($this->codcam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codcam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','I')");
       $resac = db_query("insert into db_acount values($acount,144,752,'','".AddSlashes(pg_result($resaco,0,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,753,'','".AddSlashes(pg_result($resaco,0,'nomecam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,754,'','".AddSlashes(pg_result($resaco,0,'conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,750,'','".AddSlashes(pg_result($resaco,0,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,755,'','".AddSlashes(pg_result($resaco,0,'valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,756,'','".AddSlashes(pg_result($resaco,0,'rotulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,757,'','".AddSlashes(pg_result($resaco,0,'tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,758,'','".AddSlashes(pg_result($resaco,0,'nulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,2252,'','".AddSlashes(pg_result($resaco,0,'maiusculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,2253,'','".AddSlashes(pg_result($resaco,0,'autocompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,2256,'','".AddSlashes(pg_result($resaco,0,'aceitatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,2438,'','".AddSlashes(pg_result($resaco,0,'tipoobj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,144,4792,'','".AddSlashes(pg_result($resaco,0,'rotulorel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codcam=null) { 
      $this->atualizacampos();
     $sql = " update db_syscampo set ";
     $virgula = "";
     if(trim($this->codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcam"])){ 
       $sql  .= $virgula." codcam = $this->codcam ";
       $virgula = ",";
       if(trim($this->codcam) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomecam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomecam"])){ 
       $sql  .= $virgula." nomecam = '$this->nomecam' ";
       $virgula = ",";
       if(trim($this->nomecam) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "nomecam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["conteudo"])){ 
       $sql  .= $virgula." conteudo = '$this->conteudo' ";
       $virgula = ",";
       if(trim($this->conteudo) == null ){ 
         $this->erro_sql = " Campo Tipo Campo nao Informado.";
         $this->erro_campo = "conteudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descricao"])){ 
       $sql  .= $virgula." descricao = '$this->descricao' ";
       $virgula = ",";
       if(trim($this->descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valorinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valorinicial"])){ 
       $sql  .= $virgula." valorinicial = '$this->valorinicial' ";
       $virgula = ",";
     }
     if(trim($this->rotulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rotulo"])){ 
       $sql  .= $virgula." rotulo = '$this->rotulo' ";
       $virgula = ",";
       if(trim($this->rotulo) == null ){ 
         $this->erro_sql = " Campo Rótulo nao Informado.";
         $this->erro_campo = "rotulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tamanho"])){ 
        if(trim($this->tamanho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tamanho"])){ 
           $this->tamanho = "0" ; 
        } 
       $sql  .= $virgula." tamanho = $this->tamanho ";
       $virgula = ",";
     }
     if(trim($this->nulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nulo"])){ 
       $sql  .= $virgula." nulo = '$this->nulo' ";
       $virgula = ",";
       if(trim($this->nulo) == null ){ 
         $this->erro_sql = " Campo Aceita Nulo nao Informado.";
         $this->erro_campo = "nulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->maiusculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["maiusculo"])){ 
       $sql  .= $virgula." maiusculo = '$this->maiusculo' ";
       $virgula = ",";
       if(trim($this->maiusculo) == null ){ 
         $this->erro_sql = " Campo Maiúsculo nao Informado.";
         $this->erro_campo = "maiusculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->autocompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["autocompl"])){ 
       $sql  .= $virgula." autocompl = '$this->autocompl' ";
       $virgula = ",";
       if(trim($this->autocompl) == null ){ 
         $this->erro_sql = " Campo Auto-completar nao Informado.";
         $this->erro_campo = "autocompl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->aceitatipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["aceitatipo"])){ 
       $sql  .= $virgula." aceitatipo = $this->aceitatipo ";
       $virgula = ",";
       if(trim($this->aceitatipo) == null ){ 
         $this->erro_sql = " Campo Valida nao Informado.";
         $this->erro_campo = "aceitatipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tipoobj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipoobj"])){ 
       $sql  .= $virgula." tipoobj = '$this->tipoobj' ";
       $virgula = ",";
       if(trim($this->tipoobj) == null ){ 
         $this->erro_sql = " Campo Obj. Formulário nao Informado.";
         $this->erro_campo = "tipoobj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rotulorel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rotulorel"])){ 
       $sql  .= $virgula." rotulorel = '$this->rotulorel' ";
       $virgula = ",";
       if(trim($this->rotulorel) == null ){ 
         $this->erro_sql = " Campo Rótulo relatório nao Informado.";
         $this->erro_campo = "rotulorel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codcam!=null){
       $sql .= " codcam = $this->codcam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codcam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcam"]))
           $resac = db_query("insert into db_acount values($acount,144,752,'".AddSlashes(pg_result($resaco,$conresaco,'codcam'))."','$this->codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomecam"]))
           $resac = db_query("insert into db_acount values($acount,144,753,'".AddSlashes(pg_result($resaco,$conresaco,'nomecam'))."','$this->nomecam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["conteudo"]))
           $resac = db_query("insert into db_acount values($acount,144,754,'".AddSlashes(pg_result($resaco,$conresaco,'conteudo'))."','$this->conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descricao"]))
           $resac = db_query("insert into db_acount values($acount,144,750,'".AddSlashes(pg_result($resaco,$conresaco,'descricao'))."','$this->descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valorinicial"]))
           $resac = db_query("insert into db_acount values($acount,144,755,'".AddSlashes(pg_result($resaco,$conresaco,'valorinicial'))."','$this->valorinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rotulo"]))
           $resac = db_query("insert into db_acount values($acount,144,756,'".AddSlashes(pg_result($resaco,$conresaco,'rotulo'))."','$this->rotulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tamanho"]))
           $resac = db_query("insert into db_acount values($acount,144,757,'".AddSlashes(pg_result($resaco,$conresaco,'tamanho'))."','$this->tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nulo"]))
           $resac = db_query("insert into db_acount values($acount,144,758,'".AddSlashes(pg_result($resaco,$conresaco,'nulo'))."','$this->nulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["maiusculo"]))
           $resac = db_query("insert into db_acount values($acount,144,2252,'".AddSlashes(pg_result($resaco,$conresaco,'maiusculo'))."','$this->maiusculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["autocompl"]))
           $resac = db_query("insert into db_acount values($acount,144,2253,'".AddSlashes(pg_result($resaco,$conresaco,'autocompl'))."','$this->autocompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["aceitatipo"]))
           $resac = db_query("insert into db_acount values($acount,144,2256,'".AddSlashes(pg_result($resaco,$conresaco,'aceitatipo'))."','$this->aceitatipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tipoobj"]))
           $resac = db_query("insert into db_acount values($acount,144,2438,'".AddSlashes(pg_result($resaco,$conresaco,'tipoobj'))."','$this->tipoobj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rotulorel"]))
           $resac = db_query("insert into db_acount values($acount,144,4792,'".AddSlashes(pg_result($resaco,$conresaco,'rotulorel'))."','$this->rotulorel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campos das tabelas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campos das tabelas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codcam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codcam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$codcam','E')");
         $resac = db_query("insert into db_acount values($acount,144,752,'','".AddSlashes(pg_result($resaco,$iresaco,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,753,'','".AddSlashes(pg_result($resaco,$iresaco,'nomecam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,754,'','".AddSlashes(pg_result($resaco,$iresaco,'conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,750,'','".AddSlashes(pg_result($resaco,$iresaco,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,755,'','".AddSlashes(pg_result($resaco,$iresaco,'valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,756,'','".AddSlashes(pg_result($resaco,$iresaco,'rotulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,757,'','".AddSlashes(pg_result($resaco,$iresaco,'tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,758,'','".AddSlashes(pg_result($resaco,$iresaco,'nulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,2252,'','".AddSlashes(pg_result($resaco,$iresaco,'maiusculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,2253,'','".AddSlashes(pg_result($resaco,$iresaco,'autocompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,2256,'','".AddSlashes(pg_result($resaco,$iresaco,'aceitatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,2438,'','".AddSlashes(pg_result($resaco,$iresaco,'tipoobj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,144,4792,'','".AddSlashes(pg_result($resaco,$iresaco,'rotulorel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_syscampo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codcam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcam = $codcam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campos das tabelas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codcam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campos das tabelas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codcam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codcam;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_syscampo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codcam=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_syscampo ";
     $sql2 = "";
     if($dbwhere==""){
       if($codcam!=null ){
         $sql2 .= " where db_syscampo.codcam = $codcam "; 
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
   function sql_query_file ( $codcam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_syscampo ";
     $sql2 = "";
     if($dbwhere==""){
       if($codcam!=null ){
         $sql2 .= " where db_syscampo.codcam = $codcam "; 
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