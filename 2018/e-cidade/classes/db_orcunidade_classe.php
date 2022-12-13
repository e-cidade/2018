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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcunidade
class cl_orcunidade { 
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
   var $o41_anousu = 0; 
   var $o41_orgao = 0; 
   var $o41_unidade = 0; 
   var $o41_codtri = null; 
   var $o41_descr = null; 
   var $o41_indent = 0; 
   var $o41_cnpj = null; 
   var $o41_ident = 0; 
   var $o41_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o41_anousu = int4 = Exercício 
                 o41_orgao = int4 = Órgão 
                 o41_unidade = int4 = Unidade 
                 o41_codtri = varchar(2) = Código Tribunal 
                 o41_descr = varchar(50) = Descrição Unidade 
                 o41_indent = int8 = Identificação 
                 o41_cnpj = varchar(15) = CNPJ Ministério 
                 o41_ident = int8 = Identificador Tribunal 
                 o41_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_orcunidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcunidade"); 
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
       $this->o41_anousu = ($this->o41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_anousu"]:$this->o41_anousu);
       $this->o41_orgao = ($this->o41_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_orgao"]:$this->o41_orgao);
       $this->o41_unidade = ($this->o41_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_unidade"]:$this->o41_unidade);
       $this->o41_codtri = ($this->o41_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_codtri"]:$this->o41_codtri);
       $this->o41_descr = ($this->o41_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_descr"]:$this->o41_descr);
       $this->o41_indent = ($this->o41_indent == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_indent"]:$this->o41_indent);
       $this->o41_cnpj = ($this->o41_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_cnpj"]:$this->o41_cnpj);
       $this->o41_ident = ($this->o41_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_ident"]:$this->o41_ident);
       $this->o41_instit = ($this->o41_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_instit"]:$this->o41_instit);
     }else{
       $this->o41_anousu = ($this->o41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_anousu"]:$this->o41_anousu);
       $this->o41_orgao = ($this->o41_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_orgao"]:$this->o41_orgao);
       $this->o41_unidade = ($this->o41_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o41_unidade"]:$this->o41_unidade);
     }
   }
   // funcao para inclusao
   function incluir ($o41_anousu,$o41_orgao,$o41_unidade){ 
      $this->atualizacampos();
     if($this->o41_codtri == null ){ 
       $this->erro_sql = " Campo Código Tribunal nao Informado.";
       $this->erro_campo = "o41_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o41_descr == null ){ 
       $this->erro_sql = " Campo Descrição Unidade nao Informado.";
       $this->erro_campo = "o41_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o41_indent == null ){ 
       $this->o41_indent = "0";
     }
     if($this->o41_ident == null ){ 
       $this->erro_sql = " Campo Identificador Tribunal nao Informado.";
       $this->erro_campo = "o41_ident";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o41_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o41_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o41_anousu = $o41_anousu; 
       $this->o41_orgao = $o41_orgao; 
       $this->o41_unidade = $o41_unidade; 
     if(($this->o41_anousu == null) || ($this->o41_anousu == "") ){ 
       $this->erro_sql = " Campo o41_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o41_orgao == null) || ($this->o41_orgao == "") ){ 
       $this->erro_sql = " Campo o41_orgao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o41_unidade == null) || ($this->o41_unidade == "") ){ 
       $this->erro_sql = " Campo o41_unidade nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcunidade(
                                       o41_anousu 
                                      ,o41_orgao 
                                      ,o41_unidade 
                                      ,o41_codtri 
                                      ,o41_descr 
                                      ,o41_indent 
                                      ,o41_cnpj 
                                      ,o41_ident 
                                      ,o41_instit 
                       )
                values (
                                $this->o41_anousu 
                               ,$this->o41_orgao 
                               ,$this->o41_unidade 
                               ,'$this->o41_codtri' 
                               ,'$this->o41_descr' 
                               ,$this->o41_indent 
                               ,'$this->o41_cnpj' 
                               ,$this->o41_ident 
                               ,$this->o41_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidades Orçamentárias ($this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidades Orçamentárias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidades Orçamentárias ($this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o41_anousu,$this->o41_orgao,$this->o41_unidade));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5276,'$this->o41_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5277,'$this->o41_orgao','I')");
       $resac = db_query("insert into db_acountkey values($acount,5278,'$this->o41_unidade','I')");
       $resac = db_query("insert into db_acount values($acount,757,5276,'','".AddSlashes(pg_result($resaco,0,'o41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,5277,'','".AddSlashes(pg_result($resaco,0,'o41_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,5278,'','".AddSlashes(pg_result($resaco,0,'o41_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,5279,'','".AddSlashes(pg_result($resaco,0,'o41_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,5280,'','".AddSlashes(pg_result($resaco,0,'o41_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,7398,'','".AddSlashes(pg_result($resaco,0,'o41_indent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,6424,'','".AddSlashes(pg_result($resaco,0,'o41_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,6423,'','".AddSlashes(pg_result($resaco,0,'o41_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,757,9997,'','".AddSlashes(pg_result($resaco,0,'o41_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o41_anousu=null,$o41_orgao=null,$o41_unidade=null) { 
      $this->atualizacampos();
     $sql = " update orcunidade set ";
     $virgula = "";
     if(trim($this->o41_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_anousu"])){ 
       $sql  .= $virgula." o41_anousu = $this->o41_anousu ";
       $virgula = ",";
       if(trim($this->o41_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o41_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_orgao"])){ 
       $sql  .= $virgula." o41_orgao = $this->o41_orgao ";
       $virgula = ",";
       if(trim($this->o41_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "o41_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_unidade"])){ 
       $sql  .= $virgula." o41_unidade = $this->o41_unidade ";
       $virgula = ",";
       if(trim($this->o41_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "o41_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_codtri"])){ 
       $sql  .= $virgula." o41_codtri = '$this->o41_codtri' ";
       $virgula = ",";
       if(trim($this->o41_codtri) == null ){ 
         $this->erro_sql = " Campo Código Tribunal nao Informado.";
         $this->erro_campo = "o41_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_descr"])){ 
       $sql  .= $virgula." o41_descr = '$this->o41_descr' ";
       $virgula = ",";
       if(trim($this->o41_descr) == null ){ 
         $this->erro_sql = " Campo Descrição Unidade nao Informado.";
         $this->erro_campo = "o41_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_indent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_indent"])){ 
        if(trim($this->o41_indent)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o41_indent"])){ 
           $this->o41_indent = "0" ; 
        } 
       $sql  .= $virgula." o41_indent = $this->o41_indent ";
       $virgula = ",";
     }
     if(trim($this->o41_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_cnpj"])){ 
       $sql  .= $virgula." o41_cnpj = '$this->o41_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->o41_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_ident"])){ 
       $sql  .= $virgula." o41_ident = $this->o41_ident ";
       $virgula = ",";
       if(trim($this->o41_ident) == null ){ 
         $this->erro_sql = " Campo Identificador Tribunal nao Informado.";
         $this->erro_campo = "o41_ident";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o41_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o41_instit"])){ 
       $sql  .= $virgula." o41_instit = $this->o41_instit ";
       $virgula = ",";
       if(trim($this->o41_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o41_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o41_anousu!=null){
       $sql .= " o41_anousu = $this->o41_anousu";
     }
     if($o41_orgao!=null){
       $sql .= " and  o41_orgao = $this->o41_orgao";
     }
     if($o41_unidade!=null){
       $sql .= " and  o41_unidade = $this->o41_unidade";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o41_anousu,$this->o41_orgao,$this->o41_unidade));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5276,'$this->o41_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5277,'$this->o41_orgao','A')");
         $resac = db_query("insert into db_acountkey values($acount,5278,'$this->o41_unidade','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_anousu"]))
           $resac = db_query("insert into db_acount values($acount,757,5276,'".AddSlashes(pg_result($resaco,$conresaco,'o41_anousu'))."','$this->o41_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_orgao"]))
           $resac = db_query("insert into db_acount values($acount,757,5277,'".AddSlashes(pg_result($resaco,$conresaco,'o41_orgao'))."','$this->o41_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_unidade"]))
           $resac = db_query("insert into db_acount values($acount,757,5278,'".AddSlashes(pg_result($resaco,$conresaco,'o41_unidade'))."','$this->o41_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_codtri"]))
           $resac = db_query("insert into db_acount values($acount,757,5279,'".AddSlashes(pg_result($resaco,$conresaco,'o41_codtri'))."','$this->o41_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_descr"]))
           $resac = db_query("insert into db_acount values($acount,757,5280,'".AddSlashes(pg_result($resaco,$conresaco,'o41_descr'))."','$this->o41_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_indent"]))
           $resac = db_query("insert into db_acount values($acount,757,7398,'".AddSlashes(pg_result($resaco,$conresaco,'o41_indent'))."','$this->o41_indent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_cnpj"]))
           $resac = db_query("insert into db_acount values($acount,757,6424,'".AddSlashes(pg_result($resaco,$conresaco,'o41_cnpj'))."','$this->o41_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_ident"]))
           $resac = db_query("insert into db_acount values($acount,757,6423,'".AddSlashes(pg_result($resaco,$conresaco,'o41_ident'))."','$this->o41_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o41_instit"]))
           $resac = db_query("insert into db_acount values($acount,757,9997,'".AddSlashes(pg_result($resaco,$conresaco,'o41_instit'))."','$this->o41_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades Orçamentárias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades Orçamentárias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o41_anousu."-".$this->o41_orgao."-".$this->o41_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o41_anousu=null,$o41_orgao=null,$o41_unidade=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o41_anousu,$o41_orgao,$o41_unidade));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5276,'$o41_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5277,'$o41_orgao','E')");
         $resac = db_query("insert into db_acountkey values($acount,5278,'$o41_unidade','E')");
         $resac = db_query("insert into db_acount values($acount,757,5276,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,5277,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,5278,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,5279,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,5280,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,7398,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_indent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,6424,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,6423,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,757,9997,'','".AddSlashes(pg_result($resaco,$iresaco,'o41_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcunidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o41_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o41_anousu = $o41_anousu ";
        }
        if($o41_orgao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o41_orgao = $o41_orgao ";
        }
        if($o41_unidade != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o41_unidade = $o41_unidade ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades Orçamentárias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o41_anousu."-".$o41_orgao."-".$o41_unidade;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades Orçamentárias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o41_anousu."-".$o41_orgao."-".$o41_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o41_anousu."-".$o41_orgao."-".$o41_unidade;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcunidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o41_anousu=null,$o41_orgao=null,$o41_unidade=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcunidade ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcunidade.o41_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($o41_anousu!=null ){
         $sql2 .= " where orcunidade.o41_anousu = $o41_anousu "; 
       } 
       if($o41_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcunidade.o41_orgao = $o41_orgao "; 
       } 
       if($o41_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcunidade.o41_unidade = $o41_unidade "; 
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
   function sql_query_file ( $o41_anousu=null,$o41_orgao=null,$o41_unidade=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcunidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($o41_anousu!=null ){
         $sql2 .= " where orcunidade.o41_anousu = $o41_anousu "; 
       } 
       if($o41_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcunidade.o41_orgao = $o41_orgao "; 
       } 
       if($o41_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcunidade.o41_unidade = $o41_unidade "; 
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
   function sql_query_razao ( $o41_anousu=null,$o41_orgao=null,$o41_unidade=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcunidade ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidad";
     $sql .= "      inner join db_config  on  db_config.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcdotacao on o58_unidade=o41_unidade  and o58_anousu= o41_anousu  ";
     $sql .= "      inner join empempenho on e60_anousu=o58_anousu and e60_coddot=o58_coddot ";
     $sql2 = "";
     if($dbwhere==""){
       if($o41_anousu!=null ){
         $sql2 .= " where orcunidade.o41_anousu = $o41_anousu ";
       }
       if($o41_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcunidade.o41_orgao = $o41_orgao ";
       }
      if($o41_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcunidade.o41_unidade = $o41_unidade ";
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