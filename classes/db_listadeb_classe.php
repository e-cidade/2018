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

//MODULO: caixa
//CLASSE DA ENTIDADE listadeb
class cl_listadeb { 
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
   var $k61_codigo = 0; 
   var $k61_numpre = 0; 
   var $k61_numpar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k61_codigo = int4 = Código da Lista 
                 k61_numpre = int4 = Numpre 
                 k61_numpar = int4 = Parcela 
                 ";
   //funcao construtor da classe 
   function cl_listadeb() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("listadeb"); 
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
       $this->k61_codigo = ($this->k61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_codigo"]:$this->k61_codigo);
       $this->k61_numpre = ($this->k61_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_numpre"]:$this->k61_numpre);
       $this->k61_numpar = ($this->k61_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_numpar"]:$this->k61_numpar);
     }else{
       $this->k61_codigo = ($this->k61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_codigo"]:$this->k61_codigo);
       $this->k61_numpre = ($this->k61_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_numpre"]:$this->k61_numpre);
       $this->k61_numpar = ($this->k61_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k61_numpar"]:$this->k61_numpar);
     }
   }
   // funcao para inclusao
   function incluir ($k61_codigo,$k61_numpre,$k61_numpar){ 
      $this->atualizacampos();
       $this->k61_codigo = $k61_codigo; 
       $this->k61_numpre = $k61_numpre; 
       $this->k61_numpar = $k61_numpar; 
     if(($this->k61_codigo == null) || ($this->k61_codigo == "") ){ 
       $this->erro_sql = " Campo k61_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k61_numpre == null) || ($this->k61_numpre == "") ){ 
       $this->erro_sql = " Campo k61_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k61_numpar == null) || ($this->k61_numpar == "") ){ 
       $this->erro_sql = " Campo k61_numpar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into listadeb(
                                       k61_codigo 
                                      ,k61_numpre 
                                      ,k61_numpar 
                       )
                values (
                                $this->k61_codigo 
                               ,$this->k61_numpre 
                               ,$this->k61_numpar 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Débitos da Lista ($this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Débitos da Lista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Débitos da Lista ($this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k61_codigo,$this->k61_numpre,$this->k61_numpar));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4738,'$this->k61_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4739,'$this->k61_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,4771,'$this->k61_numpar','I')");
       $resac = db_query("insert into db_acount values($acount,632,4738,'','".AddSlashes(pg_result($resaco,0,'k61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,632,4739,'','".AddSlashes(pg_result($resaco,0,'k61_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,632,4771,'','".AddSlashes(pg_result($resaco,0,'k61_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k61_codigo=null,$k61_numpre=null,$k61_numpar=null) { 
      $this->atualizacampos();
     $sql = " update listadeb set ";
     $virgula = "";
     if(trim($this->k61_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k61_codigo"])){ 
       $sql  .= $virgula." k61_codigo = $this->k61_codigo ";
       $virgula = ",";
       if(trim($this->k61_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Lista nao Informado.";
         $this->erro_campo = "k61_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k61_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k61_numpre"])){ 
       $sql  .= $virgula." k61_numpre = $this->k61_numpre ";
       $virgula = ",";
       if(trim($this->k61_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k61_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k61_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k61_numpar"])){ 
       $sql  .= $virgula." k61_numpar = $this->k61_numpar ";
       $virgula = ",";
       if(trim($this->k61_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k61_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k61_codigo!=null){
       $sql .= " k61_codigo = $this->k61_codigo";
     }
     if($k61_numpre!=null){
       $sql .= " and  k61_numpre = $this->k61_numpre";
     }
     if($k61_numpar!=null){
       $sql .= " and  k61_numpar = $this->k61_numpar";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k61_codigo,$this->k61_numpre,$this->k61_numpar));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4738,'$this->k61_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4739,'$this->k61_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,4771,'$this->k61_numpar','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k61_codigo"]))
           $resac = db_query("insert into db_acount values($acount,632,4738,'".AddSlashes(pg_result($resaco,$conresaco,'k61_codigo'))."','$this->k61_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k61_numpre"]))
           $resac = db_query("insert into db_acount values($acount,632,4739,'".AddSlashes(pg_result($resaco,$conresaco,'k61_numpre'))."','$this->k61_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k61_numpar"]))
           $resac = db_query("insert into db_acount values($acount,632,4771,'".AddSlashes(pg_result($resaco,$conresaco,'k61_numpar'))."','$this->k61_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Débitos da Lista nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Débitos da Lista nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k61_codigo=null,$k61_numpre=null,$k61_numpar=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k61_codigo,$k61_numpre,$k61_numpar));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4738,'$k61_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4739,'$k61_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,4771,'$k61_numpar','E')");
         $resac = db_query("insert into db_acount values($acount,632,4738,'','".AddSlashes(pg_result($resaco,$iresaco,'k61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,632,4739,'','".AddSlashes(pg_result($resaco,$iresaco,'k61_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,632,4771,'','".AddSlashes(pg_result($resaco,$iresaco,'k61_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listadeb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k61_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k61_codigo = $k61_codigo ";
        }
        if($k61_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k61_numpre = $k61_numpre ";
        }
        if($k61_numpar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k61_numpar = $k61_numpar ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Débitos da Lista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k61_codigo."-".$k61_numpre."-".$k61_numpar;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Débitos da Lista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k61_codigo."-".$k61_numpre."-".$k61_numpar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k61_codigo."-".$k61_numpre."-".$k61_numpar;
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
        $this->erro_sql   = "Record Vazio na Tabela:listadeb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function excluir_lista ($k61_codigo=null,$k61_numpre=null,$k61_numpar=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->k61_codigo,$this->k61_numpre,$this->k61_numpar));

     for ($iresaco=0; $iresaco < $this->numrows; $iresaco++ ) {

       if(($resaco!=false)||($this->numrows!=0)){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,4738,'".pg_result($resaco,$iresaco,'k61_codigo')."','E')");
         $resac = pg_query("insert into db_acountkey values($acount,4739,'".pg_result($resaco,$iresaco,'k61_numpre')."','E')");
         $resac = pg_query("insert into db_acountkey values($acount,4771,'".pg_result($resaco,$iresaco,'k61_numpar')."','E')");
         $resac = pg_query("insert into db_acount values($acount,632,4738,'','".pg_result($resaco,0,'k61_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,632,4739,'','".pg_result($resaco,0,'k61_numpre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,632,4771,'','".pg_result($resaco,0,'k61_numpar')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }

     }
     $sql = " delete from listadeb
                    where ";
     $sql2 = "";
      if($this->k61_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k61_codigo = $this->k61_codigo ";
}
      if($this->k61_numpre != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k61_numpre = $this->k61_numpre ";
}
      if($this->k61_numpar != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k61_numpar = $this->k61_numpar ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Débitos da Lista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Débitos da Lista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k61_codigo."-".$this->k61_numpre."-".$this->k61_numpar;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   }
   function sql_query ( $k61_codigo=null,$k61_numpre=null,$k61_numpar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listadeb ";
     $sql .= "      inner join lista  on  lista.k60_codigo = listadeb.k61_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($k61_codigo!=null ){
         $sql2 .= " where listadeb.k61_codigo = $k61_codigo "; 
       } 
       if($k61_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listadeb.k61_numpre = $k61_numpre "; 
       } 
       if($k61_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listadeb.k61_numpar = $k61_numpar "; 
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
   function sql_query_file ( $k61_codigo=null,$k61_numpre=null,$k61_numpar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listadeb ";
     $sql2 = "";
     if($dbwhere==""){
       if($k61_codigo!=null ){
         $sql2 .= " where listadeb.k61_codigo = $k61_codigo "; 
       } 
       if($k61_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listadeb.k61_numpre = $k61_numpre "; 
       } 
       if($k61_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listadeb.k61_numpar = $k61_numpar "; 
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
   function sql_query_tipodeb($k61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listadeb ";
     $sql .= "      inner join debitos  on  listadeb.k61_numpre = debitos.k22_numpre and  listadeb.k61_numpar = debitos.k22_numpar";
     $sql2 = "";
     if($dbwhere==""){
       if($k61_codigo!=null ){
         $sql2 .= " where listadeb.k61_codigo = $k61_codigo ";
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