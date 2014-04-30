<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptubaixa
class cl_iptubaixa { 
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
   var $j02_matric = 0; 
   var $j02_dtbaixa_dia = null; 
   var $j02_dtbaixa_mes = null; 
   var $j02_dtbaixa_ano = null; 
   var $j02_dtbaixa = null; 
   var $j02_motivo = null; 
   var $j02_usuario = 0; 
   var $j02_data_dia = null; 
   var $j02_data_mes = null; 
   var $j02_data_ano = null; 
   var $j02_data = null; 
   var $j02_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j02_matric = int4 = Matrícula do Imóvel 
                 j02_dtbaixa = date = Data 
                 j02_motivo = text = Motivo da baixa 
                 j02_usuario = int4 = Cod. Usuário 
                 j02_data = date = Data 
                 j02_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_iptubaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptubaixa"); 
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
       $this->j02_matric = ($this->j02_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_matric"]:$this->j02_matric);
       if($this->j02_dtbaixa == ""){
         $this->j02_dtbaixa_dia = ($this->j02_dtbaixa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_dia"]:$this->j02_dtbaixa_dia);
         $this->j02_dtbaixa_mes = ($this->j02_dtbaixa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_mes"]:$this->j02_dtbaixa_mes);
         $this->j02_dtbaixa_ano = ($this->j02_dtbaixa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_ano"]:$this->j02_dtbaixa_ano);
         if($this->j02_dtbaixa_dia != ""){
            $this->j02_dtbaixa = $this->j02_dtbaixa_ano."-".$this->j02_dtbaixa_mes."-".$this->j02_dtbaixa_dia;
         }
       }
       $this->j02_motivo = ($this->j02_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_motivo"]:$this->j02_motivo);
       $this->j02_usuario = ($this->j02_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_usuario"]:$this->j02_usuario);
       if($this->j02_data == ""){
         $this->j02_data_dia = ($this->j02_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_data_dia"]:$this->j02_data_dia);
         $this->j02_data_mes = ($this->j02_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_data_mes"]:$this->j02_data_mes);
         $this->j02_data_ano = ($this->j02_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_data_ano"]:$this->j02_data_ano);
         if($this->j02_data_dia != ""){
            $this->j02_data = $this->j02_data_ano."-".$this->j02_data_mes."-".$this->j02_data_dia;
         }
       }
       $this->j02_hora = ($this->j02_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_hora"]:$this->j02_hora);
     }else{
       $this->j02_matric = ($this->j02_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j02_matric"]:$this->j02_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j02_matric){ 
      $this->atualizacampos();
     if($this->j02_dtbaixa == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j02_dtbaixa_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j02_motivo == null ){ 
       $this->erro_sql = " Campo Motivo da baixa nao Informado.";
       $this->erro_campo = "j02_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j02_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "j02_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j02_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j02_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j02_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "j02_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j02_matric = $j02_matric; 
     if(($this->j02_matric == null) || ($this->j02_matric == "") ){ 
       $this->erro_sql = " Campo j02_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptubaixa(
                                       j02_matric 
                                      ,j02_dtbaixa 
                                      ,j02_motivo 
                                      ,j02_usuario 
                                      ,j02_data 
                                      ,j02_hora 
                       )
                values (
                                $this->j02_matric 
                               ,".($this->j02_dtbaixa == "null" || $this->j02_dtbaixa == ""?"null":"'".$this->j02_dtbaixa."'")." 
                               ,'$this->j02_motivo' 
                               ,$this->j02_usuario 
                               ,".($this->j02_data == "null" || $this->j02_data == ""?"null":"'".$this->j02_data."'")." 
                               ,'$this->j02_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela das baixas de matricula ($this->j02_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela das baixas de matricula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela das baixas de matricula ($this->j02_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j02_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j02_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9868,'$this->j02_matric','I')");
       $resac = db_query("insert into db_acount values($acount,1693,9868,'','".AddSlashes(pg_result($resaco,0,'j02_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1693,9870,'','".AddSlashes(pg_result($resaco,0,'j02_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1693,9869,'','".AddSlashes(pg_result($resaco,0,'j02_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1693,9871,'','".AddSlashes(pg_result($resaco,0,'j02_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1693,9872,'','".AddSlashes(pg_result($resaco,0,'j02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1693,9873,'','".AddSlashes(pg_result($resaco,0,'j02_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j02_matric=null) { 
      $this->atualizacampos();
     $sql = " update iptubaixa set ";
     $virgula = "";
     if(trim($this->j02_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_matric"])){ 
       $sql  .= $virgula." j02_matric = $this->j02_matric ";
       $virgula = ",";
       if(trim($this->j02_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "j02_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j02_dtbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_dia"] !="") ){ 
       $sql  .= $virgula." j02_dtbaixa = '$this->j02_dtbaixa' ";
       $virgula = ",";
       if(trim($this->j02_dtbaixa) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j02_dtbaixa_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa_dia"])){ 
         $sql  .= $virgula." j02_dtbaixa = null ";
         $virgula = ",";
         if(trim($this->j02_dtbaixa) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j02_dtbaixa_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j02_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_motivo"])){ 
       $sql  .= $virgula." j02_motivo = '$this->j02_motivo' ";
       $virgula = ",";
       if(trim($this->j02_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo da baixa nao Informado.";
         $this->erro_campo = "j02_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j02_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_usuario"])){ 
       $sql  .= $virgula." j02_usuario = $this->j02_usuario ";
       $virgula = ",";
       if(trim($this->j02_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "j02_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j02_data_dia"] !="") ){ 
       $sql  .= $virgula." j02_data = '$this->j02_data' ";
       $virgula = ",";
       if(trim($this->j02_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j02_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j02_data_dia"])){ 
         $sql  .= $virgula." j02_data = null ";
         $virgula = ",";
         if(trim($this->j02_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j02_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j02_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j02_hora"])){ 
       $sql  .= $virgula." j02_hora = '$this->j02_hora' ";
       $virgula = ",";
       if(trim($this->j02_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "j02_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j02_matric!=null){
       $sql .= " j02_matric = $this->j02_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j02_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9868,'$this->j02_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_matric"]))
           $resac = db_query("insert into db_acount values($acount,1693,9868,'".AddSlashes(pg_result($resaco,$conresaco,'j02_matric'))."','$this->j02_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_dtbaixa"]))
           $resac = db_query("insert into db_acount values($acount,1693,9870,'".AddSlashes(pg_result($resaco,$conresaco,'j02_dtbaixa'))."','$this->j02_dtbaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1693,9869,'".AddSlashes(pg_result($resaco,$conresaco,'j02_motivo'))."','$this->j02_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1693,9871,'".AddSlashes(pg_result($resaco,$conresaco,'j02_usuario'))."','$this->j02_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_data"]))
           $resac = db_query("insert into db_acount values($acount,1693,9872,'".AddSlashes(pg_result($resaco,$conresaco,'j02_data'))."','$this->j02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j02_hora"]))
           $resac = db_query("insert into db_acount values($acount,1693,9873,'".AddSlashes(pg_result($resaco,$conresaco,'j02_hora'))."','$this->j02_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela das baixas de matricula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j02_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela das baixas de matricula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j02_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j02_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j02_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j02_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9868,'$j02_matric','E')");
         $resac = db_query("insert into db_acount values($acount,1693,9868,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1693,9870,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1693,9869,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1693,9871,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1693,9872,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1693,9873,'','".AddSlashes(pg_result($resaco,$iresaco,'j02_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptubaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j02_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j02_matric = $j02_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela das baixas de matricula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j02_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela das baixas de matricula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j02_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j02_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptubaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j02_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubaixa ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptubaixa.j02_matric";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptubaixa.j02_usuario";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j02_matric!=null ){
         $sql2 .= " where iptubaixa.j02_matric = $j02_matric "; 
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
   function sql_query_file ( $j02_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($j02_matric!=null ){
         $sql2 .= " where iptubaixa.j02_matric = $j02_matric "; 
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
  
  function sql_query_loteloc($j02_matric=null, $campos="*", $ordem=null, $dbwhere=""){ 

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
    $sql .= " from iptubaixa ";
    $sql .= "      inner join iptubase     on  iptubase.j01_matric 		= iptubaixa.j02_matric";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptubaixa.j02_usuario";
    $sql .= "      inner join lote         on  lote.j34_idbql 				= iptubase.j01_idbql";
    $sql .= "      inner join cgm          on  cgm.z01_numcgm 				= iptubase.j01_numcgm";
    $sql .= "       left join loteloc      on  loteloc.j06_idbql 		  = iptubase.j01_idbql ";
    $sql .= "       left join setorloc     on setorloc.j05_codigo     = loteloc.j06_setorloc ";
    $sql2 = "";
    if($dbwhere==""){
     if($j02_matric!=null ){
       $sql2 .= " where iptubaixa.j02_matric = $j02_matric "; 
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