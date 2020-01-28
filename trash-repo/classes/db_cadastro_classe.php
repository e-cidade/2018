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

//MODULO: dbicms
//CLASSE DA ENTIDADE cadastro
class cl_cadastro { 
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
   var $anousu = 0; 
   var $cgcter = null; 
   var $cgcte = null; 
   var $cnpj = null; 
   var $razao = null; 
   var $tiporua = null; 
   var $codrua = null; 
   var $ender = null; 
   var $codbai = null; 
   var $bairro = null; 
   var $outros = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 cgcter = char(3) = Codigo do Município 
                 cgcte = char(7) = Cadastro no Tesouro do Estado 
                 cnpj = char(14) = Inscrição Federal 
                 razao = char(46) = Razão Social 
                 tiporua = char(7) = Tipo de Rua 
                 codrua = char(6) = Código da Rua 
                 ender = varchar(80) = endereco da instituicao 
                 codbai = char(4) = Código do Bairro 
                 bairro = char(35) = Bairro 
                 outros = char(35) = Outros 
                 ";
   //funcao construtor da classe 
   function cl_cadastro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadastro"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
       $this->cnpj = ($this->cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["cnpj"]:$this->cnpj);
       $this->razao = ($this->razao == ""?@$GLOBALS["HTTP_POST_VARS"]["razao"]:$this->razao);
       $this->tiporua = ($this->tiporua == ""?@$GLOBALS["HTTP_POST_VARS"]["tiporua"]:$this->tiporua);
       $this->codrua = ($this->codrua == ""?@$GLOBALS["HTTP_POST_VARS"]["codrua"]:$this->codrua);
       $this->ender = ($this->ender == ""?@$GLOBALS["HTTP_POST_VARS"]["ender"]:$this->ender);
       $this->codbai = ($this->codbai == ""?@$GLOBALS["HTTP_POST_VARS"]["codbai"]:$this->codbai);
       $this->bairro = ($this->bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["bairro"]:$this->bairro);
       $this->outros = ($this->outros == ""?@$GLOBALS["HTTP_POST_VARS"]["outros"]:$this->outros);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
     }
   }
   // funcao para inclusao
   function incluir ($anousu,$cgcter,$cgcte){ 
      $this->atualizacampos();
     if($this->cnpj == null ){ 
       $this->erro_sql = " Campo Inscrição Federal nao Informado.";
       $this->erro_campo = "cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->razao == null ){ 
       $this->erro_sql = " Campo Razão Social nao Informado.";
       $this->erro_campo = "razao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tiporua == null ){ 
       $this->erro_sql = " Campo Tipo de Rua nao Informado.";
       $this->erro_campo = "tiporua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codrua == null ){ 
       $this->erro_sql = " Campo Código da Rua nao Informado.";
       $this->erro_campo = "codrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ender == null ){ 
       $this->erro_sql = " Campo endereco da instituicao nao Informado.";
       $this->erro_campo = "ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codbai == null ){ 
       $this->erro_sql = " Campo Código do Bairro nao Informado.";
       $this->erro_campo = "codbai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->outros == null ){ 
       $this->erro_sql = " Campo Outros nao Informado.";
       $this->erro_campo = "outros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
       $this->cgcter = $cgcter; 
       $this->cgcte = $cgcte; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcte == null) || ($this->cgcte == "") ){ 
       $this->erro_sql = " Campo cgcte nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadastro(
                                       anousu 
                                      ,cgcter 
                                      ,cgcte 
                                      ,cnpj 
                                      ,razao 
                                      ,tiporua 
                                      ,codrua 
                                      ,ender 
                                      ,codbai 
                                      ,bairro 
                                      ,outros 
                       )
                values (
                                $this->anousu 
                               ,'$this->cgcter' 
                               ,'$this->cgcte' 
                               ,'$this->cnpj' 
                               ,'$this->razao' 
                               ,'$this->tiporua' 
                               ,'$this->codrua' 
                               ,'$this->ender' 
                               ,'$this->codbai' 
                               ,'$this->bairro' 
                               ,'$this->outros' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro ($this->anousu."-".$this->cgcter."-".$this->cgcte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro ($this->anousu."-".$this->cgcter."-".$this->cgcte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter."-".$this->cgcte;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter,$this->cgcte));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','I')");
       $resac = db_query("insert into db_acount values($acount,370,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2280,'','".AddSlashes(pg_result($resaco,0,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2281,'','".AddSlashes(pg_result($resaco,0,'cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2318,'','".AddSlashes(pg_result($resaco,0,'razao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2319,'','".AddSlashes(pg_result($resaco,0,'tiporua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2320,'','".AddSlashes(pg_result($resaco,0,'codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,451,'','".AddSlashes(pg_result($resaco,0,'ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2322,'','".AddSlashes(pg_result($resaco,0,'codbai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2323,'','".AddSlashes(pg_result($resaco,0,'bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,370,2324,'','".AddSlashes(pg_result($resaco,0,'outros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null,$cgcter=null,$cgcte=null) { 
      $this->atualizacampos();
     $sql = " update cadastro set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcte"])){ 
       $sql  .= $virgula." cgcte = '$this->cgcte' ";
       $virgula = ",";
       if(trim($this->cgcte) == null ){ 
         $this->erro_sql = " Campo Cadastro no Tesouro do Estado nao Informado.";
         $this->erro_campo = "cgcte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cnpj"])){ 
       $sql  .= $virgula." cnpj = '$this->cnpj' ";
       $virgula = ",";
       if(trim($this->cnpj) == null ){ 
         $this->erro_sql = " Campo Inscrição Federal nao Informado.";
         $this->erro_campo = "cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->razao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["razao"])){ 
       $sql  .= $virgula." razao = '$this->razao' ";
       $virgula = ",";
       if(trim($this->razao) == null ){ 
         $this->erro_sql = " Campo Razão Social nao Informado.";
         $this->erro_campo = "razao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tiporua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tiporua"])){ 
       $sql  .= $virgula." tiporua = '$this->tiporua' ";
       $virgula = ",";
       if(trim($this->tiporua) == null ){ 
         $this->erro_sql = " Campo Tipo de Rua nao Informado.";
         $this->erro_campo = "tiporua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codrua"])){ 
       $sql  .= $virgula." codrua = '$this->codrua' ";
       $virgula = ",";
       if(trim($this->codrua) == null ){ 
         $this->erro_sql = " Campo Código da Rua nao Informado.";
         $this->erro_campo = "codrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ender"])){ 
       $sql  .= $virgula." ender = '$this->ender' ";
       $virgula = ",";
       if(trim($this->ender) == null ){ 
         $this->erro_sql = " Campo endereco da instituicao nao Informado.";
         $this->erro_campo = "ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codbai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codbai"])){ 
       $sql  .= $virgula." codbai = '$this->codbai' ";
       $virgula = ",";
       if(trim($this->codbai) == null ){ 
         $this->erro_sql = " Campo Código do Bairro nao Informado.";
         $this->erro_campo = "codbai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bairro"])){ 
       $sql  .= $virgula." bairro = '$this->bairro' ";
       $virgula = ",";
       if(trim($this->bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->outros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["outros"])){ 
       $sql  .= $virgula." outros = '$this->outros' ";
       $virgula = ",";
       if(trim($this->outros) == null ){ 
         $this->erro_sql = " Campo Outros nao Informado.";
         $this->erro_campo = "outros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($anousu!=null){
       $sql .= " anousu = $this->anousu";
     }
     if($cgcter!=null){
       $sql .= " and  cgcter = '$this->cgcter'";
     }
     if($cgcte!=null){
       $sql .= " and  cgcte = '$this->cgcte'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->cgcter,$this->cgcte));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,370,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,370,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcte"]))
           $resac = db_query("insert into db_acount values($acount,370,2280,'".AddSlashes(pg_result($resaco,$conresaco,'cgcte'))."','$this->cgcte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cnpj"]))
           $resac = db_query("insert into db_acount values($acount,370,2281,'".AddSlashes(pg_result($resaco,$conresaco,'cnpj'))."','$this->cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["razao"]))
           $resac = db_query("insert into db_acount values($acount,370,2318,'".AddSlashes(pg_result($resaco,$conresaco,'razao'))."','$this->razao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tiporua"]))
           $resac = db_query("insert into db_acount values($acount,370,2319,'".AddSlashes(pg_result($resaco,$conresaco,'tiporua'))."','$this->tiporua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codrua"]))
           $resac = db_query("insert into db_acount values($acount,370,2320,'".AddSlashes(pg_result($resaco,$conresaco,'codrua'))."','$this->codrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ender"]))
           $resac = db_query("insert into db_acount values($acount,370,451,'".AddSlashes(pg_result($resaco,$conresaco,'ender'))."','$this->ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codbai"]))
           $resac = db_query("insert into db_acount values($acount,370,2322,'".AddSlashes(pg_result($resaco,$conresaco,'codbai'))."','$this->codbai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bairro"]))
           $resac = db_query("insert into db_acount values($acount,370,2323,'".AddSlashes(pg_result($resaco,$conresaco,'bairro'))."','$this->bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["outros"]))
           $resac = db_query("insert into db_acount values($acount,370,2324,'".AddSlashes(pg_result($resaco,$conresaco,'outros'))."','$this->outros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter."-".$this->cgcte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter."-".$this->cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->cgcter."-".$this->cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$cgcter=null,$cgcte=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu,$cgcter,$cgcte));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$cgcte','E')");
         $resac = db_query("insert into db_acount values($acount,370,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2280,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2281,'','".AddSlashes(pg_result($resaco,$iresaco,'cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2318,'','".AddSlashes(pg_result($resaco,$iresaco,'razao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2319,'','".AddSlashes(pg_result($resaco,$iresaco,'tiporua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2320,'','".AddSlashes(pg_result($resaco,$iresaco,'codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,451,'','".AddSlashes(pg_result($resaco,$iresaco,'ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2322,'','".AddSlashes(pg_result($resaco,$iresaco,'codbai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2323,'','".AddSlashes(pg_result($resaco,$iresaco,'bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,370,2324,'','".AddSlashes(pg_result($resaco,$iresaco,'outros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadastro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
        if($cgcte != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcte = '$cgcte' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu."-".$cgcter."-".$cgcte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter."-".$cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$cgcter."-".$cgcte;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadastro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>