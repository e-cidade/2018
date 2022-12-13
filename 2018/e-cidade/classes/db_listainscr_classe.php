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
//CLASSE DA ENTIDADE listainscr
class cl_listainscr { 
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
   var $p12_codigo = 0; 
   var $p12_inscr = 0; 
   var $p12_cnpj = null; 
   var $p12_fone = null; 
   var $p12_tipolanc = 1; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p12_codigo = int4 = Código 
                 p12_inscr = int4 = Inscrição 
                 p12_cnpj = varchar(20) = CNPJ 
                 p12_fone = varchar(12) = Telefone 
                 p12_tipolanc = int4 = Tipo Lancamento 
                 ";
   //funcao construtor da classe 
   function cl_listainscr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("listainscr"); 
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
       $this->p12_codigo = ($this->p12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_codigo"]:$this->p12_codigo);
       $this->p12_inscr = ($this->p12_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_inscr"]:$this->p12_inscr);
       $this->p12_cnpj = ($this->p12_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_cnpj"]:$this->p12_cnpj);
       $this->p12_fone = ($this->p12_fone == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_fone"]:$this->p12_fone);
       $this->p12_tipolanc = ($this->p12_tipolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_tipolanc"]:$this->p12_tipolanc);
     }else{
       $this->p12_codigo = ($this->p12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_codigo"]:$this->p12_codigo);
       $this->p12_inscr = ($this->p12_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["p12_inscr"]:$this->p12_inscr);
     }
   }
   // funcao para inclusao
   function incluir ($p12_codigo,$p12_inscr){ 
      $this->atualizacampos();
     if($this->p12_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ nao Informado.";
       $this->erro_campo = "p12_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p12_tipolanc == null ){ 
       $this->erro_sql = " Campo Tipo Lancamento nao Informado.";
       $this->erro_campo = "p12_tipolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p12_codigo = $p12_codigo; 
       $this->p12_inscr = $p12_inscr; 
     if(($this->p12_codigo == null) || ($this->p12_codigo == "") ){ 
       $this->erro_sql = " Campo p12_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p12_inscr == null) || ($this->p12_inscr == "") ){ 
       $this->erro_sql = " Campo p12_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into listainscr(
                                       p12_codigo 
                                      ,p12_inscr 
                                      ,p12_cnpj 
                                      ,p12_fone 
                                      ,p12_tipolanc 
                       )
                values (
                                $this->p12_codigo 
                               ,$this->p12_inscr 
                               ,'$this->p12_cnpj' 
                               ,'$this->p12_fone' 
                               ,$this->p12_tipolanc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lista das incrições dos escritórios ($this->p12_codigo."-".$this->p12_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lista das incrições dos escritórios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lista das incrições dos escritórios ($this->p12_codigo."-".$this->p12_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p12_codigo."-".$this->p12_inscr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p12_codigo,$this->p12_inscr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5175,'$this->p12_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,5176,'$this->p12_inscr','I')");
       $resac = db_query("insert into db_acount values($acount,743,5175,'','".AddSlashes(pg_result($resaco,0,'p12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,743,5176,'','".AddSlashes(pg_result($resaco,0,'p12_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,743,5177,'','".AddSlashes(pg_result($resaco,0,'p12_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,743,5178,'','".AddSlashes(pg_result($resaco,0,'p12_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,743,14586,'','".AddSlashes(pg_result($resaco,0,'p12_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p12_codigo=null,$p12_inscr=null) { 
      $this->atualizacampos();
     $sql = " update listainscr set ";
     $virgula = "";
     if(trim($this->p12_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p12_codigo"])){ 
       $sql  .= $virgula." p12_codigo = $this->p12_codigo ";
       $virgula = ",";
       if(trim($this->p12_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "p12_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p12_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p12_inscr"])){ 
       $sql  .= $virgula." p12_inscr = $this->p12_inscr ";
       $virgula = ",";
       if(trim($this->p12_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição nao Informado.";
         $this->erro_campo = "p12_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p12_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p12_cnpj"])){ 
       $sql  .= $virgula." p12_cnpj = '$this->p12_cnpj' ";
       $virgula = ",";
       if(trim($this->p12_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ nao Informado.";
         $this->erro_campo = "p12_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p12_fone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p12_fone"])){ 
       $sql  .= $virgula." p12_fone = '$this->p12_fone' ";
       $virgula = ",";
     }
     if(trim($this->p12_tipolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p12_tipolanc"])){ 
       $sql  .= $virgula." p12_tipolanc = $this->p12_tipolanc ";
       $virgula = ",";
       if(trim($this->p12_tipolanc) == null ){ 
         $this->erro_sql = " Campo Tipo Lancamento nao Informado.";
         $this->erro_campo = "p12_tipolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p12_codigo!=null){
       $sql .= " p12_codigo = $this->p12_codigo";
     }
     if($p12_inscr!=null){
       $sql .= " and  p12_inscr = $this->p12_inscr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p12_codigo,$this->p12_inscr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5175,'$this->p12_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,5176,'$this->p12_inscr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p12_codigo"]) || $this->p12_codigo != "")
           $resac = db_query("insert into db_acount values($acount,743,5175,'".AddSlashes(pg_result($resaco,$conresaco,'p12_codigo'))."','$this->p12_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p12_inscr"]) || $this->p12_inscr != "")
           $resac = db_query("insert into db_acount values($acount,743,5176,'".AddSlashes(pg_result($resaco,$conresaco,'p12_inscr'))."','$this->p12_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p12_cnpj"]) || $this->p12_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,743,5177,'".AddSlashes(pg_result($resaco,$conresaco,'p12_cnpj'))."','$this->p12_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p12_fone"]) || $this->p12_fone != "")
           $resac = db_query("insert into db_acount values($acount,743,5178,'".AddSlashes(pg_result($resaco,$conresaco,'p12_fone'))."','$this->p12_fone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p12_tipolanc"]) || $this->p12_tipolanc != "")
           $resac = db_query("insert into db_acount values($acount,743,14586,'".AddSlashes(pg_result($resaco,$conresaco,'p12_tipolanc'))."','$this->p12_tipolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lista das incrições dos escritórios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p12_codigo."-".$this->p12_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lista das incrições dos escritórios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p12_codigo."-".$this->p12_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p12_codigo."-".$this->p12_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p12_codigo=null,$p12_inscr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p12_codigo,$p12_inscr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5175,'$p12_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,5176,'$p12_inscr','E')");
         $resac = db_query("insert into db_acount values($acount,743,5175,'','".AddSlashes(pg_result($resaco,$iresaco,'p12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,743,5176,'','".AddSlashes(pg_result($resaco,$iresaco,'p12_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,743,5177,'','".AddSlashes(pg_result($resaco,$iresaco,'p12_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,743,5178,'','".AddSlashes(pg_result($resaco,$iresaco,'p12_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,743,14586,'','".AddSlashes(pg_result($resaco,$iresaco,'p12_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listainscr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p12_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p12_codigo = $p12_codigo ";
        }
        if($p12_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p12_inscr = $p12_inscr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lista das incrições dos escritórios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p12_codigo."-".$p12_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lista das incrições dos escritórios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p12_codigo."-".$p12_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p12_codigo."-".$p12_inscr;
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
        $this->erro_sql   = "Record Vazio na Tabela:listainscr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p12_codigo=null,$p12_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listainscr ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = listainscr.p12_inscr";
     $sql .= "      inner join listainscrcab  on  listainscrcab.p11_codigo = listainscr.p12_codigo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = listainscrcab.p11_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($p12_codigo!=null ){
         $sql2 .= " where listainscr.p12_codigo = $p12_codigo "; 
       } 
       if($p12_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listainscr.p12_inscr = $p12_inscr "; 
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
   // funcao do sql 
   function sql_query_file ( $p12_codigo=null,$p12_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listainscr ";
     $sql2 = "";
     if($dbwhere==""){
       if($p12_codigo!=null ){
         $sql2 .= " where listainscr.p12_codigo = $p12_codigo "; 
       } 
       if($p12_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listainscr.p12_inscr = $p12_inscr "; 
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