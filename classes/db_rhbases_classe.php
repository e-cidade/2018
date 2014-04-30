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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhbases
class cl_rhbases { 
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
   var $rh32_base = null; 
   var $rh32_descr = null; 
   var $rh32_calqua = 'f'; 
   var $rh32_mesant = 'f'; 
   var $rh32_pfixo = 'f'; 
   var $rh32_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh32_base = varchar(4) = Base 
                 rh32_descr = varchar(30) = Descrição da Base 
                 rh32_calqua = bool = Calculo pela Quantidade (s/n) 
                 rh32_mesant = bool = Pesquisa valores mes anterior 
                 rh32_pfixo = bool = calcular pelo ponto fixo 
                 rh32_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_rhbases() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhbases"); 
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
       $this->rh32_base = ($this->rh32_base == ""?@$GLOBALS["HTTP_POST_VARS"]["rh32_base"]:$this->rh32_base);
       $this->rh32_descr = ($this->rh32_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh32_descr"]:$this->rh32_descr);
       $this->rh32_calqua = ($this->rh32_calqua == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh32_calqua"]:$this->rh32_calqua);
       $this->rh32_mesant = ($this->rh32_mesant == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh32_mesant"]:$this->rh32_mesant);
       $this->rh32_pfixo = ($this->rh32_pfixo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh32_pfixo"]:$this->rh32_pfixo);
       $this->rh32_instit = ($this->rh32_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh32_instit"]:$this->rh32_instit);
     }else{
       $this->rh32_base = ($this->rh32_base == ""?@$GLOBALS["HTTP_POST_VARS"]["rh32_base"]:$this->rh32_base);
     }
   }
   // funcao para inclusao
   function incluir ($rh32_base){ 
      $this->atualizacampos();
     if($this->rh32_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Base nao Informado.";
       $this->erro_campo = "rh32_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh32_calqua == null ){ 
       $this->erro_sql = " Campo Calculo pela Quantidade (s/n) nao Informado.";
       $this->erro_campo = "rh32_calqua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh32_mesant == null ){ 
       $this->erro_sql = " Campo Pesquisa valores mes anterior nao Informado.";
       $this->erro_campo = "rh32_mesant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh32_pfixo == null ){ 
       $this->erro_sql = " Campo calcular pelo ponto fixo nao Informado.";
       $this->erro_campo = "rh32_pfixo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh32_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "rh32_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh32_base = $rh32_base; 
     if(($this->rh32_base == null) || ($this->rh32_base == "") ){ 
       $this->erro_sql = " Campo rh32_base nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhbases(
                                       rh32_base 
                                      ,rh32_descr 
                                      ,rh32_calqua 
                                      ,rh32_mesant 
                                      ,rh32_pfixo 
                                      ,rh32_instit 
                       )
                values (
                                '$this->rh32_base' 
                               ,'$this->rh32_descr' 
                               ,'$this->rh32_calqua' 
                               ,'$this->rh32_mesant' 
                               ,'$this->rh32_pfixo' 
                               ,$this->rh32_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bases para cálculo ($this->rh32_base) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bases para cálculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bases para cálculo ($this->rh32_base) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh32_base;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh32_base));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7157,'$this->rh32_base','I')");
       $resac = db_query("insert into db_acount values($acount,1187,7157,'','".AddSlashes(pg_result($resaco,0,'rh32_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1187,7158,'','".AddSlashes(pg_result($resaco,0,'rh32_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1187,7159,'','".AddSlashes(pg_result($resaco,0,'rh32_calqua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1187,7160,'','".AddSlashes(pg_result($resaco,0,'rh32_mesant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1187,7161,'','".AddSlashes(pg_result($resaco,0,'rh32_pfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1187,7469,'','".AddSlashes(pg_result($resaco,0,'rh32_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh32_base=null) { 
      $this->atualizacampos();
     $sql = " update rhbases set ";
     $virgula = "";
     if(trim($this->rh32_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_base"])){ 
       $sql  .= $virgula." rh32_base = '$this->rh32_base' ";
       $virgula = ",";
       if(trim($this->rh32_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "rh32_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh32_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_descr"])){ 
       $sql  .= $virgula." rh32_descr = '$this->rh32_descr' ";
       $virgula = ",";
       if(trim($this->rh32_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da Base nao Informado.";
         $this->erro_campo = "rh32_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh32_calqua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_calqua"])){ 
       $sql  .= $virgula." rh32_calqua = '$this->rh32_calqua' ";
       $virgula = ",";
       if(trim($this->rh32_calqua) == null ){ 
         $this->erro_sql = " Campo Calculo pela Quantidade (s/n) nao Informado.";
         $this->erro_campo = "rh32_calqua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh32_mesant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_mesant"])){ 
       $sql  .= $virgula." rh32_mesant = '$this->rh32_mesant' ";
       $virgula = ",";
       if(trim($this->rh32_mesant) == null ){ 
         $this->erro_sql = " Campo Pesquisa valores mes anterior nao Informado.";
         $this->erro_campo = "rh32_mesant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh32_pfixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_pfixo"])){ 
       $sql  .= $virgula." rh32_pfixo = '$this->rh32_pfixo' ";
       $virgula = ",";
       if(trim($this->rh32_pfixo) == null ){ 
         $this->erro_sql = " Campo calcular pelo ponto fixo nao Informado.";
         $this->erro_campo = "rh32_pfixo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh32_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh32_instit"])){ 
       $sql  .= $virgula." rh32_instit = $this->rh32_instit ";
       $virgula = ",";
       if(trim($this->rh32_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "rh32_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh32_base!=null){
       $sql .= " rh32_base = '$this->rh32_base'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh32_base));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7157,'$this->rh32_base','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_base"]))
           $resac = db_query("insert into db_acount values($acount,1187,7157,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_base'))."','$this->rh32_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_descr"]))
           $resac = db_query("insert into db_acount values($acount,1187,7158,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_descr'))."','$this->rh32_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_calqua"]))
           $resac = db_query("insert into db_acount values($acount,1187,7159,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_calqua'))."','$this->rh32_calqua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_mesant"]))
           $resac = db_query("insert into db_acount values($acount,1187,7160,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_mesant'))."','$this->rh32_mesant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_pfixo"]))
           $resac = db_query("insert into db_acount values($acount,1187,7161,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_pfixo'))."','$this->rh32_pfixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh32_instit"]))
           $resac = db_query("insert into db_acount values($acount,1187,7469,'".AddSlashes(pg_result($resaco,$conresaco,'rh32_instit'))."','$this->rh32_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases para cálculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh32_base;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases para cálculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh32_base;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh32_base;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh32_base=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh32_base));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7157,'$rh32_base','E')");
         $resac = db_query("insert into db_acount values($acount,1187,7157,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1187,7158,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1187,7159,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_calqua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1187,7160,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_mesant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1187,7161,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_pfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1187,7469,'','".AddSlashes(pg_result($resaco,$iresaco,'rh32_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhbases
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh32_base != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh32_base = '$rh32_base' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bases para cálculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh32_base;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bases para cálculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh32_base;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh32_base;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhbases";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh32_base=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbases ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhbases.rh32_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh32_base!=null ){
         $sql2 .= " where rhbases.rh32_base = '$rh32_base' "; 
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
   function sql_query_file ( $rh32_base=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbases ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh32_base!=null ){
         $sql2 .= " where rhbases.rh32_base = '$rh32_base' "; 
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
   function sql_query_rubricas ( $rh32_base=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhbases ";
     $sql .= "     left outer join rhbasesr on rhbases.rh32_base   = rhbasesr.rh33_base ";
     $sql .= "                             and rhbases.rh32_instit = rhbasesr.rh33_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh32_base!=null ){
         $sql2 .= " where rhbases.rh32_base = '$rh32_base' "; 
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