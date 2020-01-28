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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscalprocrec
class cl_fiscalprocrec { 
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
   var $y45_codtipo = 0; 
   var $y45_receit = 0; 
   var $y45_valor = 0; 
   var $y45_descr = null; 
   var $y45_vlrfixo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y45_codtipo = int8 = C�digo da Proced�ncia 
                 y45_receit = int4 = codigo da receita 
                 y45_valor = float8 = Valor padr�o para a receita 
                 y45_descr = varchar(50) = Descri��o padr�o da receita 
                 y45_vlrfixo = bool = Valor Fixo 
                 ";
   //funcao construtor da classe 
   function cl_fiscalprocrec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscalprocrec"); 
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
       $this->y45_codtipo = ($this->y45_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_codtipo"]:$this->y45_codtipo);
       $this->y45_receit = ($this->y45_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_receit"]:$this->y45_receit);
       $this->y45_valor = ($this->y45_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_valor"]:$this->y45_valor);
       $this->y45_descr = ($this->y45_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_descr"]:$this->y45_descr);
       $this->y45_vlrfixo = ($this->y45_vlrfixo == "f"?@$GLOBALS["HTTP_POST_VARS"]["y45_vlrfixo"]:$this->y45_vlrfixo);
     }else{
       $this->y45_codtipo = ($this->y45_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_codtipo"]:$this->y45_codtipo);
       $this->y45_receit = ($this->y45_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["y45_receit"]:$this->y45_receit);
     }
   }
   // funcao para inclusao
   function incluir ($y45_codtipo,$y45_receit){ 
      $this->atualizacampos();
     if($this->y45_valor == null ){ 
       $this->erro_sql = " Campo Valor padr�o para a receita nao Informado.";
       $this->erro_campo = "y45_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y45_descr == null ){ 
       $this->erro_sql = " Campo Descri��o padr�o da receita nao Informado.";
       $this->erro_campo = "y45_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y45_vlrfixo == null ){ 
       $this->erro_sql = " Campo Valor Fixo nao Informado.";
       $this->erro_campo = "y45_vlrfixo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y45_codtipo = $y45_codtipo; 
       $this->y45_receit = $y45_receit; 
     if(($this->y45_codtipo == null) || ($this->y45_codtipo == "") ){ 
       $this->erro_sql = " Campo y45_codtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y45_receit == null) || ($this->y45_receit == "") ){ 
       $this->erro_sql = " Campo y45_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscalprocrec(
                                       y45_codtipo 
                                      ,y45_receit 
                                      ,y45_valor 
                                      ,y45_descr 
                                      ,y45_vlrfixo 
                       )
                values (
                                $this->y45_codtipo 
                               ,$this->y45_receit 
                               ,$this->y45_valor 
                               ,'$this->y45_descr' 
                               ,'$this->y45_vlrfixo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscalprocrec ($this->y45_codtipo."-".$this->y45_receit) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscalprocrec j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscalprocrec ($this->y45_codtipo."-".$this->y45_receit) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y45_codtipo."-".$this->y45_receit;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y45_codtipo,$this->y45_receit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4937,'$this->y45_codtipo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4938,'$this->y45_receit','I')");
       $resac = db_query("insert into db_acount values($acount,682,4937,'','".AddSlashes(pg_result($resaco,0,'y45_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,682,4938,'','".AddSlashes(pg_result($resaco,0,'y45_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,682,4939,'','".AddSlashes(pg_result($resaco,0,'y45_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,682,4940,'','".AddSlashes(pg_result($resaco,0,'y45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,682,6627,'','".AddSlashes(pg_result($resaco,0,'y45_vlrfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y45_codtipo=null,$y45_receit=null) { 
      $this->atualizacampos();
     $sql = " update fiscalprocrec set ";
     $virgula = "";
     if(trim($this->y45_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y45_codtipo"])){ 
       $sql  .= $virgula." y45_codtipo = $this->y45_codtipo ";
       $virgula = ",";
       if(trim($this->y45_codtipo) == null ){ 
         $this->erro_sql = " Campo C�digo da Proced�ncia nao Informado.";
         $this->erro_campo = "y45_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y45_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y45_receit"])){ 
       $sql  .= $virgula." y45_receit = $this->y45_receit ";
       $virgula = ",";
       if(trim($this->y45_receit) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "y45_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y45_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y45_valor"])){ 
       $sql  .= $virgula." y45_valor = $this->y45_valor ";
       $virgula = ",";
       if(trim($this->y45_valor) == null ){ 
         $this->erro_sql = " Campo Valor padr�o para a receita nao Informado.";
         $this->erro_campo = "y45_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y45_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y45_descr"])){ 
       $sql  .= $virgula." y45_descr = '$this->y45_descr' ";
       $virgula = ",";
       if(trim($this->y45_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o padr�o da receita nao Informado.";
         $this->erro_campo = "y45_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y45_vlrfixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y45_vlrfixo"])){ 
       $sql  .= $virgula." y45_vlrfixo = '$this->y45_vlrfixo' ";
       $virgula = ",";
       if(trim($this->y45_vlrfixo) == null ){ 
         $this->erro_sql = " Campo Valor Fixo nao Informado.";
         $this->erro_campo = "y45_vlrfixo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y45_codtipo!=null){
       $sql .= " y45_codtipo = $this->y45_codtipo";
     }
     if($y45_receit!=null){
       $sql .= " and  y45_receit = $this->y45_receit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y45_codtipo,$this->y45_receit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4937,'$this->y45_codtipo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4938,'$this->y45_receit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y45_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,682,4937,'".AddSlashes(pg_result($resaco,$conresaco,'y45_codtipo'))."','$this->y45_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y45_receit"]))
           $resac = db_query("insert into db_acount values($acount,682,4938,'".AddSlashes(pg_result($resaco,$conresaco,'y45_receit'))."','$this->y45_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y45_valor"]))
           $resac = db_query("insert into db_acount values($acount,682,4939,'".AddSlashes(pg_result($resaco,$conresaco,'y45_valor'))."','$this->y45_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y45_descr"]))
           $resac = db_query("insert into db_acount values($acount,682,4940,'".AddSlashes(pg_result($resaco,$conresaco,'y45_descr'))."','$this->y45_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y45_vlrfixo"]))
           $resac = db_query("insert into db_acount values($acount,682,6627,'".AddSlashes(pg_result($resaco,$conresaco,'y45_vlrfixo'))."','$this->y45_vlrfixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalprocrec nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y45_codtipo."-".$this->y45_receit;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalprocrec nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y45_codtipo."-".$this->y45_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y45_codtipo."-".$this->y45_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y45_codtipo=null,$y45_receit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y45_codtipo,$y45_receit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4937,'$y45_codtipo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4938,'$y45_receit','E')");
         $resac = db_query("insert into db_acount values($acount,682,4937,'','".AddSlashes(pg_result($resaco,$iresaco,'y45_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,682,4938,'','".AddSlashes(pg_result($resaco,$iresaco,'y45_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,682,4939,'','".AddSlashes(pg_result($resaco,$iresaco,'y45_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,682,4940,'','".AddSlashes(pg_result($resaco,$iresaco,'y45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,682,6627,'','".AddSlashes(pg_result($resaco,$iresaco,'y45_vlrfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscalprocrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y45_codtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y45_codtipo = $y45_codtipo ";
        }
        if($y45_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y45_receit = $y45_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalprocrec nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y45_codtipo."-".$y45_receit;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalprocrec nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y45_codtipo."-".$y45_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y45_codtipo."-".$y45_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:fiscalprocrec";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y45_codtipo=null,$y45_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalprocrec ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = fiscalprocrec.y45_receit";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = fiscalprocrec.y45_codtipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql2 = "";
     if($dbwhere==""){
       if($y45_codtipo!=null ){
         $sql2 .= " where fiscalprocrec.y45_codtipo = $y45_codtipo "; 
       } 
       if($y45_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalprocrec.y45_receit = $y45_receit "; 
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
   function sql_query_autotipo ( $y45_codtipo=null,$y45_receit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from fiscalprocrec ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = fiscalprocrec.y45_receit";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = fiscalprocrec.y45_codtipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join autotipo  on  fiscalprocrec.y45_codtipo = autotipo.y59_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y45_codtipo!=null ){
         $sql2 .= " where fiscalprocrec.y45_codtipo = $y45_codtipo ";
       }
       if($y45_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " fiscalprocrec.y45_receit = $y45_receit ";
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
   function sql_query_file ( $y45_codtipo=null,$y45_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalprocrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($y45_codtipo!=null ){
         $sql2 .= " where fiscalprocrec.y45_codtipo = $y45_codtipo "; 
       } 
       if($y45_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalprocrec.y45_receit = $y45_receit "; 
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
   function sql_query_fiscaltipo ( $y45_codtipo=null,$y45_receit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from fiscalprocrec ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = fiscalprocrec.y45_receit";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = fiscalprocrec.y45_codtipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join fiscaltipo  on  fiscalprocrec.y45_codtipo = fiscaltipo.y31_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y45_codtipo!=null ){
         $sql2 .= " where fiscalprocrec.y45_codtipo = $y45_codtipo ";
       }
       if($y45_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " fiscalprocrec.y45_receit = $y45_receit ";
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