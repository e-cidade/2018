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
//CLASSE DA ENTIDADE conlancambol
class cl_conlancambol { 
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
   var $c77_codlan = 0; 
   var $c77_anousu = 0; 
   var $c77_boletim = 0; 
   var $c77_databol_dia = null; 
   var $c77_databol_mes = null; 
   var $c77_databol_ano = null; 
   var $c77_databol = null; 
   var $c77_dataproc_dia = null; 
   var $c77_dataproc_mes = null; 
   var $c77_dataproc_ano = null; 
   var $c77_dataproc = null; 
   var $c77_valor = 0; 
   var $c77_instit = 0; 
   var $c77_id = 0; 
   var $c77_autent = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c77_codlan = int8 = codigo do lançamento 
                 c77_anousu = int4 = ano 
                 c77_boletim = float8 = numero do boletim 
                 c77_databol = date = data do boletim 
                 c77_dataproc = date = data_proc 
                 c77_valor = float8 = valor 
                 c77_instit = int4 = insituição 
                 c77_id = int4 = Autenticação 
                 c77_autent = int4 = Código Autenticação 
                 ";
   //funcao construtor da classe 
   function cl_conlancambol() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancambol"); 
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
       $this->c77_codlan = ($this->c77_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_codlan"]:$this->c77_codlan);
       $this->c77_anousu = ($this->c77_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_anousu"]:$this->c77_anousu);
       $this->c77_boletim = ($this->c77_boletim == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_boletim"]:$this->c77_boletim);
       if($this->c77_databol == ""){
         $this->c77_databol_dia = ($this->c77_databol_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_databol_dia"]:$this->c77_databol_dia);
         $this->c77_databol_mes = ($this->c77_databol_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_databol_mes"]:$this->c77_databol_mes);
         $this->c77_databol_ano = ($this->c77_databol_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_databol_ano"]:$this->c77_databol_ano);
         if($this->c77_databol_dia != ""){
            $this->c77_databol = $this->c77_databol_ano."-".$this->c77_databol_mes."-".$this->c77_databol_dia;
         }
       }
       if($this->c77_dataproc == ""){
         $this->c77_dataproc_dia = ($this->c77_dataproc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_dataproc_dia"]:$this->c77_dataproc_dia);
         $this->c77_dataproc_mes = ($this->c77_dataproc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_dataproc_mes"]:$this->c77_dataproc_mes);
         $this->c77_dataproc_ano = ($this->c77_dataproc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_dataproc_ano"]:$this->c77_dataproc_ano);
         if($this->c77_dataproc_dia != ""){
            $this->c77_dataproc = $this->c77_dataproc_ano."-".$this->c77_dataproc_mes."-".$this->c77_dataproc_dia;
         }
       }
       $this->c77_valor = ($this->c77_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_valor"]:$this->c77_valor);
       $this->c77_instit = ($this->c77_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_instit"]:$this->c77_instit);
       $this->c77_id = ($this->c77_id == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_id"]:$this->c77_id);
       $this->c77_autent = ($this->c77_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_autent"]:$this->c77_autent);
     }else{
       $this->c77_codlan = ($this->c77_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c77_codlan"]:$this->c77_codlan);
     }
   }
   // funcao para inclusao
   function incluir ($c77_codlan){ 
      $this->atualizacampos();
     if($this->c77_anousu == null ){ 
       $this->erro_sql = " Campo ano nao Informado.";
       $this->erro_campo = "c77_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_boletim == null ){ 
       $this->erro_sql = " Campo numero do boletim nao Informado.";
       $this->erro_campo = "c77_boletim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_databol == null ){ 
       $this->erro_sql = " Campo data do boletim nao Informado.";
       $this->erro_campo = "c77_databol_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_dataproc == null ){ 
       $this->erro_sql = " Campo data_proc nao Informado.";
       $this->erro_campo = "c77_dataproc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "c77_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_instit == null ){ 
       $this->erro_sql = " Campo insituição nao Informado.";
       $this->erro_campo = "c77_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c77_id == null ){ 
       $this->c77_id = "0";
     }
     if($this->c77_autent == null ){ 
       $this->c77_autent = "0";
     }
       $this->c77_codlan = $c77_codlan; 
     if(($this->c77_codlan == null) || ($this->c77_codlan == "") ){ 
       $this->erro_sql = " Campo c77_codlan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancambol(
                                       c77_codlan 
                                      ,c77_anousu 
                                      ,c77_boletim 
                                      ,c77_databol 
                                      ,c77_dataproc 
                                      ,c77_valor 
                                      ,c77_instit 
                                      ,c77_id 
                                      ,c77_autent 
                       )
                values (
                                $this->c77_codlan 
                               ,$this->c77_anousu 
                               ,$this->c77_boletim 
                               ,".($this->c77_databol == "null" || $this->c77_databol == ""?"null":"'".$this->c77_databol."'")." 
                               ,".($this->c77_dataproc == "null" || $this->c77_dataproc == ""?"null":"'".$this->c77_dataproc."'")." 
                               ,$this->c77_valor 
                               ,$this->c77_instit 
                               ,$this->c77_id 
                               ,$this->c77_autent 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "c77 ($this->c77_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "c77 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "c77 ($this->c77_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c77_codlan;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c77_codlan));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6605,'$this->c77_codlan','I')");
       $resac = db_query("insert into db_acount values($acount,1087,6605,'','".AddSlashes(pg_result($resaco,0,'c77_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6606,'','".AddSlashes(pg_result($resaco,0,'c77_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6609,'','".AddSlashes(pg_result($resaco,0,'c77_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6611,'','".AddSlashes(pg_result($resaco,0,'c77_databol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6607,'','".AddSlashes(pg_result($resaco,0,'c77_dataproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6608,'','".AddSlashes(pg_result($resaco,0,'c77_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,6610,'','".AddSlashes(pg_result($resaco,0,'c77_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,8857,'','".AddSlashes(pg_result($resaco,0,'c77_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1087,8858,'','".AddSlashes(pg_result($resaco,0,'c77_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c77_codlan=null) { 
      $this->atualizacampos();
     $sql = " update conlancambol set ";
     $virgula = "";
     if(trim($this->c77_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_codlan"])){ 
       $sql  .= $virgula." c77_codlan = $this->c77_codlan ";
       $virgula = ",";
       if(trim($this->c77_codlan) == null ){ 
         $this->erro_sql = " Campo codigo do lançamento nao Informado.";
         $this->erro_campo = "c77_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c77_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_anousu"])){ 
       $sql  .= $virgula." c77_anousu = $this->c77_anousu ";
       $virgula = ",";
       if(trim($this->c77_anousu) == null ){ 
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "c77_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c77_boletim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_boletim"])){ 
       $sql  .= $virgula." c77_boletim = $this->c77_boletim ";
       $virgula = ",";
       if(trim($this->c77_boletim) == null ){ 
         $this->erro_sql = " Campo numero do boletim nao Informado.";
         $this->erro_campo = "c77_boletim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c77_databol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_databol_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c77_databol_dia"] !="") ){ 
       $sql  .= $virgula." c77_databol = '$this->c77_databol' ";
       $virgula = ",";
       if(trim($this->c77_databol) == null ){ 
         $this->erro_sql = " Campo data do boletim nao Informado.";
         $this->erro_campo = "c77_databol_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c77_databol_dia"])){ 
         $sql  .= $virgula." c77_databol = null ";
         $virgula = ",";
         if(trim($this->c77_databol) == null ){ 
           $this->erro_sql = " Campo data do boletim nao Informado.";
           $this->erro_campo = "c77_databol_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c77_dataproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_dataproc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c77_dataproc_dia"] !="") ){ 
       $sql  .= $virgula." c77_dataproc = '$this->c77_dataproc' ";
       $virgula = ",";
       if(trim($this->c77_dataproc) == null ){ 
         $this->erro_sql = " Campo data_proc nao Informado.";
         $this->erro_campo = "c77_dataproc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c77_dataproc_dia"])){ 
         $sql  .= $virgula." c77_dataproc = null ";
         $virgula = ",";
         if(trim($this->c77_dataproc) == null ){ 
           $this->erro_sql = " Campo data_proc nao Informado.";
           $this->erro_campo = "c77_dataproc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c77_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_valor"])){ 
       $sql  .= $virgula." c77_valor = $this->c77_valor ";
       $virgula = ",";
       if(trim($this->c77_valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "c77_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c77_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_instit"])){ 
       $sql  .= $virgula." c77_instit = $this->c77_instit ";
       $virgula = ",";
       if(trim($this->c77_instit) == null ){ 
         $this->erro_sql = " Campo insituição nao Informado.";
         $this->erro_campo = "c77_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c77_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_id"])){ 
        if(trim($this->c77_id)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c77_id"])){ 
           $this->c77_id = "0" ; 
        } 
       $sql  .= $virgula." c77_id = $this->c77_id ";
       $virgula = ",";
     }
     if(trim($this->c77_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c77_autent"])){ 
        if(trim($this->c77_autent)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c77_autent"])){ 
           $this->c77_autent = "0" ; 
        } 
       $sql  .= $virgula." c77_autent = $this->c77_autent ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c77_codlan!=null){
       $sql .= " c77_codlan = $this->c77_codlan";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c77_codlan));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6605,'$this->c77_codlan','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_codlan"]))
           $resac = db_query("insert into db_acount values($acount,1087,6605,'".AddSlashes(pg_result($resaco,$conresaco,'c77_codlan'))."','$this->c77_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1087,6606,'".AddSlashes(pg_result($resaco,$conresaco,'c77_anousu'))."','$this->c77_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_boletim"]))
           $resac = db_query("insert into db_acount values($acount,1087,6609,'".AddSlashes(pg_result($resaco,$conresaco,'c77_boletim'))."','$this->c77_boletim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_databol"]))
           $resac = db_query("insert into db_acount values($acount,1087,6611,'".AddSlashes(pg_result($resaco,$conresaco,'c77_databol'))."','$this->c77_databol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_dataproc"]))
           $resac = db_query("insert into db_acount values($acount,1087,6607,'".AddSlashes(pg_result($resaco,$conresaco,'c77_dataproc'))."','$this->c77_dataproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_valor"]))
           $resac = db_query("insert into db_acount values($acount,1087,6608,'".AddSlashes(pg_result($resaco,$conresaco,'c77_valor'))."','$this->c77_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_instit"]))
           $resac = db_query("insert into db_acount values($acount,1087,6610,'".AddSlashes(pg_result($resaco,$conresaco,'c77_instit'))."','$this->c77_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_id"]))
           $resac = db_query("insert into db_acount values($acount,1087,8857,'".AddSlashes(pg_result($resaco,$conresaco,'c77_id'))."','$this->c77_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c77_autent"]))
           $resac = db_query("insert into db_acount values($acount,1087,8858,'".AddSlashes(pg_result($resaco,$conresaco,'c77_autent'))."','$this->c77_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c77 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c77_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c77 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c77_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c77_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c77_codlan=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c77_codlan));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6605,'$c77_codlan','E')");
         $resac = db_query("insert into db_acount values($acount,1087,6605,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6606,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6609,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6611,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_databol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6607,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_dataproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6608,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,6610,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,8857,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1087,8858,'','".AddSlashes(pg_result($resaco,$iresaco,'c77_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancambol
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c77_codlan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c77_codlan = $c77_codlan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c77 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c77_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c77 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c77_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c77_codlan;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancambol";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c77_codlan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancambol ";
     $sql .= "      inner join boletim  on  boletim.k11_data = conlancambol.c77_databol and  boletim.k11_instit = conlancambol.c77_instit";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancambol.c77_codlan";
     $sql .= "      inner join db_config  on  db_config.codigo = boletim.k11_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = boletim.k11_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($c77_codlan!=null ){
         $sql2 .= " where conlancambol.c77_codlan = $c77_codlan "; 
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
   function sql_query_file ( $c77_codlan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancambol ";
     $sql2 = "";
     if($dbwhere==""){
       if($c77_codlan!=null ){
         $sql2 .= " where conlancambol.c77_codlan = $c77_codlan "; 
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