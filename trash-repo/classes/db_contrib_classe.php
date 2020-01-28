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

//MODULO: contrib
//CLASSE DA ENTIDADE contrib
class cl_contrib { 
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
   var $d07_contri = 0; 
   var $d07_matric = 0; 
   var $d07_idbql = 0; 
   var $d07_vlrdes = 0; 
   var $d07_valor = 0; 
   var $d07_data_dia = null; 
   var $d07_data_mes = null; 
   var $d07_data_ano = null; 
   var $d07_data = null; 
   var $d07_venal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d07_contri = int4 = Contribuicao 
                 d07_matric = int4 = Matricula 
                 d07_idbql = int4 = Codigo do Lote 
                 d07_vlrdes = float8 = Valor do desconto 
                 d07_valor = float8 = Valor a Cobrar 
                 d07_data = date = Data inclusao 
                 d07_venal = float8 = Valor venal 
                 ";
   //funcao construtor da classe 
   function cl_contrib() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contrib"); 
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
       $this->d07_contri = ($this->d07_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_contri"]:$this->d07_contri);
       $this->d07_matric = ($this->d07_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_matric"]:$this->d07_matric);
       $this->d07_idbql = ($this->d07_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_idbql"]:$this->d07_idbql);
       $this->d07_vlrdes = ($this->d07_vlrdes == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_vlrdes"]:$this->d07_vlrdes);
       $this->d07_valor = ($this->d07_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_valor"]:$this->d07_valor);
       if($this->d07_data == ""){
         $this->d07_data_dia = ($this->d07_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_data_dia"]:$this->d07_data_dia);
         $this->d07_data_mes = ($this->d07_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_data_mes"]:$this->d07_data_mes);
         $this->d07_data_ano = ($this->d07_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_data_ano"]:$this->d07_data_ano);
         if($this->d07_data_dia != ""){
            $this->d07_data = $this->d07_data_ano."-".$this->d07_data_mes."-".$this->d07_data_dia;
         }
       }
       $this->d07_venal = ($this->d07_venal == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_venal"]:$this->d07_venal);
     }else{
       $this->d07_contri = ($this->d07_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_contri"]:$this->d07_contri);
       $this->d07_matric = ($this->d07_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["d07_matric"]:$this->d07_matric);
     }
   }
   // funcao para inclusao
   function incluir ($d07_contri,$d07_matric){ 
      $this->atualizacampos();
     if($this->d07_idbql == null ){ 
       $this->erro_sql = " Campo Codigo do Lote nao Informado.";
       $this->erro_campo = "d07_idbql";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d07_vlrdes == null ){ 
       $this->erro_sql = " Campo Valor do desconto nao Informado.";
       $this->erro_campo = "d07_vlrdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d07_valor == null ){ 
       $this->erro_sql = " Campo Valor a Cobrar nao Informado.";
       $this->erro_campo = "d07_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d07_data == null ){ 
       $this->erro_sql = " Campo Data inclusao nao Informado.";
       $this->erro_campo = "d07_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d07_venal == null ){ 
       $this->erro_sql = " Campo Valor venal nao Informado.";
       $this->erro_campo = "d07_venal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d07_contri = $d07_contri; 
       $this->d07_matric = $d07_matric; 
     if(($this->d07_contri == null) || ($this->d07_contri == "") ){ 
       $this->erro_sql = " Campo d07_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d07_matric == null) || ($this->d07_matric == "") ){ 
       $this->erro_sql = " Campo d07_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contrib(
                                       d07_contri 
                                      ,d07_matric 
                                      ,d07_idbql 
                                      ,d07_vlrdes 
                                      ,d07_valor 
                                      ,d07_data 
                                      ,d07_venal 
                       )
                values (
                                $this->d07_contri 
                               ,$this->d07_matric 
                               ,$this->d07_idbql 
                               ,$this->d07_vlrdes 
                               ,$this->d07_valor 
                               ,".($this->d07_data == "null" || $this->d07_data == ""?"null":"'".$this->d07_data."'")." 
                               ,$this->d07_venal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d07_contri."-".$this->d07_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d07_contri."-".$this->d07_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d07_contri."-".$this->d07_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d07_contri,$this->d07_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,707,'$this->d07_contri','I')");
       $resac = db_query("insert into db_acountkey values($acount,708,'$this->d07_matric','I')");
       $resac = db_query("insert into db_acount values($acount,132,707,'','".AddSlashes(pg_result($resaco,0,'d07_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,708,'','".AddSlashes(pg_result($resaco,0,'d07_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,709,'','".AddSlashes(pg_result($resaco,0,'d07_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,710,'','".AddSlashes(pg_result($resaco,0,'d07_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,711,'','".AddSlashes(pg_result($resaco,0,'d07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,712,'','".AddSlashes(pg_result($resaco,0,'d07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,132,4770,'','".AddSlashes(pg_result($resaco,0,'d07_venal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d07_contri=null,$d07_matric=null) { 
      $this->atualizacampos();
     $sql = " update contrib set ";
     $virgula = "";
     if(trim($this->d07_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_contri"])){ 
        if(trim($this->d07_contri)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_contri"])){ 
           $this->d07_contri = "0" ; 
        } 
       $sql  .= $virgula." d07_contri = $this->d07_contri ";
       $virgula = ",";
       if(trim($this->d07_contri) == null ){ 
         $this->erro_sql = " Campo Contribuicao nao Informado.";
         $this->erro_campo = "d07_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d07_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_matric"])){ 
        if(trim($this->d07_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_matric"])){ 
           $this->d07_matric = "0" ; 
        } 
       $sql  .= $virgula." d07_matric = $this->d07_matric ";
       $virgula = ",";
       if(trim($this->d07_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "d07_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d07_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_idbql"])){ 
        if(trim($this->d07_idbql)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_idbql"])){ 
           $this->d07_idbql = "0" ; 
        } 
       $sql  .= $virgula." d07_idbql = $this->d07_idbql ";
       $virgula = ",";
       if(trim($this->d07_idbql) == null ){ 
         $this->erro_sql = " Campo Codigo do Lote nao Informado.";
         $this->erro_campo = "d07_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d07_vlrdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_vlrdes"])){ 
        if(trim($this->d07_vlrdes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_vlrdes"])){ 
           $this->d07_vlrdes = "0" ; 
        } 
       $sql  .= $virgula." d07_vlrdes = $this->d07_vlrdes ";
       $virgula = ",";
       if(trim($this->d07_vlrdes) == null ){ 
         $this->erro_sql = " Campo Valor do desconto nao Informado.";
         $this->erro_campo = "d07_vlrdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d07_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_valor"])){ 
        if(trim($this->d07_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_valor"])){ 
           $this->d07_valor = "0" ; 
        } 
       $sql  .= $virgula." d07_valor = $this->d07_valor ";
       $virgula = ",";
       if(trim($this->d07_valor) == null ){ 
         $this->erro_sql = " Campo Valor a Cobrar nao Informado.";
         $this->erro_campo = "d07_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d07_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d07_data_dia"] !="") ){ 
       $sql  .= $virgula." d07_data = '$this->d07_data' ";
       $virgula = ",";
       if(trim($this->d07_data) == null ){ 
         $this->erro_sql = " Campo Data inclusao nao Informado.";
         $this->erro_campo = "d07_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d07_data_dia"])){ 
         $sql  .= $virgula." d07_data = null ";
         $virgula = ",";
         if(trim($this->d07_data) == null ){ 
           $this->erro_sql = " Campo Data inclusao nao Informado.";
           $this->erro_campo = "d07_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d07_venal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d07_venal"])){ 
        if(trim($this->d07_venal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d07_venal"])){ 
           $this->d07_venal = "0" ; 
        } 
       $sql  .= $virgula." d07_venal = $this->d07_venal ";
       $virgula = ",";
       if(trim($this->d07_venal) == null ){ 
         $this->erro_sql = " Campo Valor venal nao Informado.";
         $this->erro_campo = "d07_venal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d07_contri!=null){
       $sql .= " d07_contri = $this->d07_contri";
     }
     if($d07_matric!=null){
       $sql .= " and  d07_matric = $this->d07_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d07_contri,$this->d07_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,707,'$this->d07_contri','A')");
         $resac = db_query("insert into db_acountkey values($acount,708,'$this->d07_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_contri"]))
           $resac = db_query("insert into db_acount values($acount,132,707,'".AddSlashes(pg_result($resaco,$conresaco,'d07_contri'))."','$this->d07_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_matric"]))
           $resac = db_query("insert into db_acount values($acount,132,708,'".AddSlashes(pg_result($resaco,$conresaco,'d07_matric'))."','$this->d07_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_idbql"]))
           $resac = db_query("insert into db_acount values($acount,132,709,'".AddSlashes(pg_result($resaco,$conresaco,'d07_idbql'))."','$this->d07_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_vlrdes"]))
           $resac = db_query("insert into db_acount values($acount,132,710,'".AddSlashes(pg_result($resaco,$conresaco,'d07_vlrdes'))."','$this->d07_vlrdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_valor"]))
           $resac = db_query("insert into db_acount values($acount,132,711,'".AddSlashes(pg_result($resaco,$conresaco,'d07_valor'))."','$this->d07_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_data"]))
           $resac = db_query("insert into db_acount values($acount,132,712,'".AddSlashes(pg_result($resaco,$conresaco,'d07_data'))."','$this->d07_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d07_venal"]))
           $resac = db_query("insert into db_acount values($acount,132,4770,'".AddSlashes(pg_result($resaco,$conresaco,'d07_venal'))."','$this->d07_venal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d07_contri."-".$this->d07_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d07_contri."-".$this->d07_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d07_contri."-".$this->d07_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d07_contri=null,$d07_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d07_contri,$d07_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,707,'$d07_contri','E')");
         $resac = db_query("insert into db_acountkey values($acount,708,'$d07_matric','E')");
         $resac = db_query("insert into db_acount values($acount,132,707,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,708,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,709,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,710,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,711,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,712,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,132,4770,'','".AddSlashes(pg_result($resaco,$iresaco,'d07_venal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contrib
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d07_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d07_contri = $d07_contri ";
        }
        if($d07_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d07_matric = $d07_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d07_contri."-".$d07_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d07_contri."-".$d07_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d07_contri."-".$d07_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:contrib";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d07_contri=null,$d07_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contrib ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = contrib.d07_matric";
     $sql .= "      inner join contlot  on  contlot.d05_contri = contrib.d07_contri and  contlot.d05_idbql = contrib.d07_idbql";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join lote  as a on   a.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  on  editalrua.d02_contri = contlot.d05_contri";
     $sql .= "      inner join lote  as b on   b.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  as c on   c.d02_contri = contlot.d05_contri";
     $sql2 = "";
     if($dbwhere==""){
       if($d07_contri!=null ){
         $sql2 .= " where contrib.d07_contri = $d07_contri "; 
       } 
       if($d07_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contrib.d07_matric = $d07_matric "; 
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
   function sql_query_file ( $d07_contri=null,$d07_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contrib ";
     $sql2 = "";
     if($dbwhere==""){
       if($d07_contri!=null ){
         $sql2 .= " where contrib.d07_contri = $d07_contri "; 
       } 
       if($d07_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " contrib.d07_matric = $d07_matric "; 
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
   function sql_query_not ( $d07_contri=null,$d07_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contrib ";
     $sql .= "      inner join iptubase on  iptubase.j01_matric = contrib.d07_matric";
     $sql .= "      inner join contlot  on  contlot.d05_contri = contrib.d07_contri and  contlot.d05_idbql = contrib.d07_idbql";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join lote  as a on   a.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  on  editalrua.d02_contri = contlot.d05_contri";
     $sql .= "      inner join lote  as b on   b.j34_idbql = contlot.d05_idbql";
     $sql .= "      inner join editalrua  as c on   c.d02_contri = contlot.d05_contri";
		 $sql .= "      left  join contricalc on d09_contri = d07_contri ";
		 $sql .= "                           and d09_matric = d07_matric ";
     $sql .= "      left outer join contrinot on d09_sequencial = d08_contricalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($d07_contri!=null ){
         $sql2 .= " where contrib.d07_contri = $d07_contri ";
       }
       if($d07_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " contrib.d07_matric = $d07_matric ";
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