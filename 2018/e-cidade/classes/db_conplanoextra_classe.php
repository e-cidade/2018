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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoextra
class cl_conplanoextra { 
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
   var $c33_anousu = 0; 
   var $c33_instit = 0; 
   var $c33_reduz = 0; 
   var $c33_tipo = null; 
   var $c33_codcla = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c33_anousu = int4 = Exercício 
                 c33_instit = int4 = Instituição 
                 c33_reduz = int4 = Reduzido 
                 c33_tipo = char(1) = Tipo 
                 c33_codcla = int4 = Classificação 
                 ";
   //funcao construtor da classe 
   function cl_conplanoextra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoextra"); 
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
       $this->c33_anousu = ($this->c33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_anousu"]:$this->c33_anousu);
       $this->c33_instit = ($this->c33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_instit"]:$this->c33_instit);
       $this->c33_reduz = ($this->c33_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_reduz"]:$this->c33_reduz);
       $this->c33_tipo = ($this->c33_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_tipo"]:$this->c33_tipo);
       $this->c33_codcla = ($this->c33_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_codcla"]:$this->c33_codcla);
     }else{
       $this->c33_anousu = ($this->c33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_anousu"]:$this->c33_anousu);
       $this->c33_instit = ($this->c33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_instit"]:$this->c33_instit);
       $this->c33_reduz = ($this->c33_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c33_reduz"]:$this->c33_reduz);
     }
   }
   // funcao para inclusao
   function incluir ($c33_anousu,$c33_instit,$c33_reduz){ 
      $this->atualizacampos();
     if($this->c33_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "c33_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c33_codcla == null ){ 
       $this->erro_sql = " Campo Classificação nao Informado.";
       $this->erro_campo = "c33_codcla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c33_anousu = $c33_anousu; 
       $this->c33_instit = $c33_instit; 
       $this->c33_reduz = $c33_reduz; 
     if(($this->c33_anousu == null) || ($this->c33_anousu == "") ){ 
       $this->erro_sql = " Campo c33_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c33_instit == null) || ($this->c33_instit == "") ){ 
       $this->erro_sql = " Campo c33_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c33_reduz == null) || ($this->c33_reduz == "") ){ 
       $this->erro_sql = " Campo c33_reduz nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoextra(
                                       c33_anousu 
                                      ,c33_instit 
                                      ,c33_reduz 
                                      ,c33_tipo 
                                      ,c33_codcla 
                       )
                values (
                                $this->c33_anousu 
                               ,$this->c33_instit 
                               ,$this->c33_reduz 
                               ,'$this->c33_tipo' 
                               ,$this->c33_codcla 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "c33 ($this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "c33 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "c33 ($this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c33_anousu,$this->c33_instit,$this->c33_reduz));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8250,'$this->c33_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,8251,'$this->c33_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,8252,'$this->c33_reduz','I')");
       $resac = db_query("insert into db_acount values($acount,1390,8250,'','".AddSlashes(pg_result($resaco,0,'c33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1390,8251,'','".AddSlashes(pg_result($resaco,0,'c33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1390,8252,'','".AddSlashes(pg_result($resaco,0,'c33_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1390,8253,'','".AddSlashes(pg_result($resaco,0,'c33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1390,8254,'','".AddSlashes(pg_result($resaco,0,'c33_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c33_anousu=null,$c33_instit=null,$c33_reduz=null) { 
      $this->atualizacampos();
     $sql = " update conplanoextra set ";
     $virgula = "";
     if(trim($this->c33_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c33_anousu"])){ 
       $sql  .= $virgula." c33_anousu = $this->c33_anousu ";
       $virgula = ",";
       if(trim($this->c33_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c33_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c33_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c33_instit"])){ 
       $sql  .= $virgula." c33_instit = $this->c33_instit ";
       $virgula = ",";
       if(trim($this->c33_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c33_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c33_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c33_reduz"])){ 
       $sql  .= $virgula." c33_reduz = $this->c33_reduz ";
       $virgula = ",";
       if(trim($this->c33_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c33_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c33_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c33_tipo"])){ 
       $sql  .= $virgula." c33_tipo = '$this->c33_tipo' ";
       $virgula = ",";
       if(trim($this->c33_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "c33_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c33_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c33_codcla"])){ 
       $sql  .= $virgula." c33_codcla = $this->c33_codcla ";
       $virgula = ",";
       if(trim($this->c33_codcla) == null ){ 
         $this->erro_sql = " Campo Classificação nao Informado.";
         $this->erro_campo = "c33_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c33_anousu!=null){
       $sql .= " c33_anousu = $this->c33_anousu";
     }
     if($c33_instit!=null){
       $sql .= " and  c33_instit = $this->c33_instit";
     }
     if($c33_reduz!=null){
       $sql .= " and  c33_reduz = $this->c33_reduz";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c33_anousu,$this->c33_instit,$this->c33_reduz));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8250,'$this->c33_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,8251,'$this->c33_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,8252,'$this->c33_reduz','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c33_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1390,8250,'".AddSlashes(pg_result($resaco,$conresaco,'c33_anousu'))."','$this->c33_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c33_instit"]))
           $resac = db_query("insert into db_acount values($acount,1390,8251,'".AddSlashes(pg_result($resaco,$conresaco,'c33_instit'))."','$this->c33_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c33_reduz"]))
           $resac = db_query("insert into db_acount values($acount,1390,8252,'".AddSlashes(pg_result($resaco,$conresaco,'c33_reduz'))."','$this->c33_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c33_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1390,8253,'".AddSlashes(pg_result($resaco,$conresaco,'c33_tipo'))."','$this->c33_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c33_codcla"]))
           $resac = db_query("insert into db_acount values($acount,1390,8254,'".AddSlashes(pg_result($resaco,$conresaco,'c33_codcla'))."','$this->c33_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c33 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c33 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c33_anousu."-".$this->c33_instit."-".$this->c33_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c33_anousu=null,$c33_instit=null,$c33_reduz=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c33_anousu,$c33_instit,$c33_reduz));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8250,'$c33_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,8251,'$c33_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,8252,'$c33_reduz','E')");
         $resac = db_query("insert into db_acount values($acount,1390,8250,'','".AddSlashes(pg_result($resaco,$iresaco,'c33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1390,8251,'','".AddSlashes(pg_result($resaco,$iresaco,'c33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1390,8252,'','".AddSlashes(pg_result($resaco,$iresaco,'c33_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1390,8253,'','".AddSlashes(pg_result($resaco,$iresaco,'c33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1390,8254,'','".AddSlashes(pg_result($resaco,$iresaco,'c33_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoextra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c33_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c33_anousu = $c33_anousu ";
        }
        if($c33_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c33_instit = $c33_instit ";
        }
        if($c33_reduz != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c33_reduz = $c33_reduz ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c33 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c33_anousu."-".$c33_instit."-".$c33_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c33 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c33_anousu."-".$c33_instit."-".$c33_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c33_anousu."-".$c33_instit."-".$c33_reduz;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoextra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>