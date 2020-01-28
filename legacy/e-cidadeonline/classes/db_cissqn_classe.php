<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: ISSQN
//CLASSE DA ENTIDADE cissqn
class cl_cissqn { 
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
   var $q04_anousu = 0; 
   var $q04_inflat = null; 
   var $q04_vbase = 0; 
   var $q04_dtbase_dia = null; 
   var $q04_dtbase_mes = null; 
   var $q04_dtbase_ano = null; 
   var $q04_dtbase = null; 
   var $q04_proced = 0; 
   var $q04_calfixvar = 0; 
   var $q04_diasvcto = 0; 
   var $q04_perccorrepadrao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q04_anousu = int4 = ano 
                 q04_inflat = varchar(5) = inflator 
                 q04_vbase = float8 = valor base 
                 q04_dtbase = date = data base 
                 q04_proced = int4 = codigo da procedencia 
                 q04_calfixvar = int4 = Como proceder qdo inscricao for fixo e variavel 
                 q04_diasvcto = int4 = Dias padrao para vencimento 
                 q04_perccorrepadrao = float4 = Percentual Correção Padrão 
                 ";
   //funcao construtor da classe 
   function cl_cissqn() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cissqn"); 
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
       $this->q04_anousu = ($this->q04_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_anousu"]:$this->q04_anousu);
       $this->q04_inflat = ($this->q04_inflat == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_inflat"]:$this->q04_inflat);
       $this->q04_vbase = ($this->q04_vbase == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_vbase"]:$this->q04_vbase);
       if($this->q04_dtbase == ""){
         $this->q04_dtbase_dia = ($this->q04_dtbase_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_dtbase_dia"]:$this->q04_dtbase_dia);
         $this->q04_dtbase_mes = ($this->q04_dtbase_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_dtbase_mes"]:$this->q04_dtbase_mes);
         $this->q04_dtbase_ano = ($this->q04_dtbase_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_dtbase_ano"]:$this->q04_dtbase_ano);
         if($this->q04_dtbase_dia != ""){
            $this->q04_dtbase = $this->q04_dtbase_ano."-".$this->q04_dtbase_mes."-".$this->q04_dtbase_dia;
         }
       }
       $this->q04_proced = ($this->q04_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_proced"]:$this->q04_proced);
       $this->q04_calfixvar = ($this->q04_calfixvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_calfixvar"]:$this->q04_calfixvar);
       $this->q04_diasvcto = ($this->q04_diasvcto == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_diasvcto"]:$this->q04_diasvcto);
       $this->q04_perccorrepadrao = ($this->q04_perccorrepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_perccorrepadrao"]:$this->q04_perccorrepadrao);
     }else{
       $this->q04_anousu = ($this->q04_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q04_anousu"]:$this->q04_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($q04_anousu){ 
      $this->atualizacampos();
     if($this->q04_inflat == null ){ 
       $this->erro_sql = " Campo inflator nao Informado.";
       $this->erro_campo = "q04_inflat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_vbase == null ){ 
       $this->erro_sql = " Campo valor base nao Informado.";
       $this->erro_campo = "q04_vbase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_dtbase == null ){ 
       $this->erro_sql = " Campo data base nao Informado.";
       $this->erro_campo = "q04_dtbase_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_proced == null ){ 
       $this->erro_sql = " Campo codigo da procedencia nao Informado.";
       $this->erro_campo = "q04_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_calfixvar == null ){ 
       $this->erro_sql = " Campo Como proceder qdo inscricao for fixo e variavel nao Informado.";
       $this->erro_campo = "q04_calfixvar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_diasvcto == null ){ 
       $this->erro_sql = " Campo Dias padrao para vencimento nao Informado.";
       $this->erro_campo = "q04_diasvcto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q04_perccorrepadrao == null ){ 
       $this->erro_sql = " Campo Percentual Correção Padrão nao Informado.";
       $this->erro_campo = "q04_perccorrepadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q04_anousu = $q04_anousu; 
     if(($this->q04_anousu == null) || ($this->q04_anousu == "") ){ 
       $this->erro_sql = " Campo q04_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cissqn(
                                       q04_anousu 
                                      ,q04_inflat 
                                      ,q04_vbase 
                                      ,q04_dtbase 
                                      ,q04_proced 
                                      ,q04_calfixvar 
                                      ,q04_diasvcto 
                                      ,q04_perccorrepadrao 
                       )
                values (
                                $this->q04_anousu 
                               ,'$this->q04_inflat' 
                               ,$this->q04_vbase 
                               ,".($this->q04_dtbase == "null" || $this->q04_dtbase == ""?"null":"'".$this->q04_dtbase."'")." 
                               ,$this->q04_proced 
                               ,$this->q04_calfixvar 
                               ,$this->q04_diasvcto 
                               ,$this->q04_perccorrepadrao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q04_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q04_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q04_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q04_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,270,'$this->q04_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,55,270,'','".AddSlashes(pg_result($resaco,0,'q04_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,272,'','".AddSlashes(pg_result($resaco,0,'q04_inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,273,'','".AddSlashes(pg_result($resaco,0,'q04_vbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,274,'','".AddSlashes(pg_result($resaco,0,'q04_dtbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,6538,'','".AddSlashes(pg_result($resaco,0,'q04_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,8210,'','".AddSlashes(pg_result($resaco,0,'q04_calfixvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,8211,'','".AddSlashes(pg_result($resaco,0,'q04_diasvcto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,55,17404,'','".AddSlashes(pg_result($resaco,0,'q04_perccorrepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q04_anousu=null) { 
      $this->atualizacampos();
     $sql = " update cissqn set ";
     $virgula = "";
     if(trim($this->q04_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_anousu"])){ 
       $sql  .= $virgula." q04_anousu = $this->q04_anousu ";
       $virgula = ",";
       if(trim($this->q04_anousu) == null ){ 
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q04_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_inflat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_inflat"])){ 
       $sql  .= $virgula." q04_inflat = '$this->q04_inflat' ";
       $virgula = ",";
       if(trim($this->q04_inflat) == null ){ 
         $this->erro_sql = " Campo inflator nao Informado.";
         $this->erro_campo = "q04_inflat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_vbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_vbase"])){ 
       $sql  .= $virgula." q04_vbase = $this->q04_vbase ";
       $virgula = ",";
       if(trim($this->q04_vbase) == null ){ 
         $this->erro_sql = " Campo valor base nao Informado.";
         $this->erro_campo = "q04_vbase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_dtbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_dtbase_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q04_dtbase_dia"] !="") ){ 
       $sql  .= $virgula." q04_dtbase = '$this->q04_dtbase' ";
       $virgula = ",";
       if(trim($this->q04_dtbase) == null ){ 
         $this->erro_sql = " Campo data base nao Informado.";
         $this->erro_campo = "q04_dtbase_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q04_dtbase_dia"])){ 
         $sql  .= $virgula." q04_dtbase = null ";
         $virgula = ",";
         if(trim($this->q04_dtbase) == null ){ 
           $this->erro_sql = " Campo data base nao Informado.";
           $this->erro_campo = "q04_dtbase_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q04_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_proced"])){ 
       $sql  .= $virgula." q04_proced = $this->q04_proced ";
       $virgula = ",";
       if(trim($this->q04_proced) == null ){ 
         $this->erro_sql = " Campo codigo da procedencia nao Informado.";
         $this->erro_campo = "q04_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_calfixvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_calfixvar"])){ 
       $sql  .= $virgula." q04_calfixvar = $this->q04_calfixvar ";
       $virgula = ",";
       if(trim($this->q04_calfixvar) == null ){ 
         $this->erro_sql = " Campo Como proceder qdo inscricao for fixo e variavel nao Informado.";
         $this->erro_campo = "q04_calfixvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_diasvcto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_diasvcto"])){ 
       $sql  .= $virgula." q04_diasvcto = $this->q04_diasvcto ";
       $virgula = ",";
       if(trim($this->q04_diasvcto) == null ){ 
         $this->erro_sql = " Campo Dias padrao para vencimento nao Informado.";
         $this->erro_campo = "q04_diasvcto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q04_perccorrepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q04_perccorrepadrao"])){ 
       $sql  .= $virgula." q04_perccorrepadrao = $this->q04_perccorrepadrao ";
       $virgula = ",";
       if(trim($this->q04_perccorrepadrao) == null ){ 
         $this->erro_sql = " Campo Percentual Correção Padrão nao Informado.";
         $this->erro_campo = "q04_perccorrepadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q04_anousu!=null){
       $sql .= " q04_anousu = $this->q04_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q04_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,270,'$this->q04_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_anousu"]) || $this->q04_anousu != "")
           $resac = db_query("insert into db_acount values($acount,55,270,'".AddSlashes(pg_result($resaco,$conresaco,'q04_anousu'))."','$this->q04_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_inflat"]) || $this->q04_inflat != "")
           $resac = db_query("insert into db_acount values($acount,55,272,'".AddSlashes(pg_result($resaco,$conresaco,'q04_inflat'))."','$this->q04_inflat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_vbase"]) || $this->q04_vbase != "")
           $resac = db_query("insert into db_acount values($acount,55,273,'".AddSlashes(pg_result($resaco,$conresaco,'q04_vbase'))."','$this->q04_vbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_dtbase"]) || $this->q04_dtbase != "")
           $resac = db_query("insert into db_acount values($acount,55,274,'".AddSlashes(pg_result($resaco,$conresaco,'q04_dtbase'))."','$this->q04_dtbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_proced"]) || $this->q04_proced != "")
           $resac = db_query("insert into db_acount values($acount,55,6538,'".AddSlashes(pg_result($resaco,$conresaco,'q04_proced'))."','$this->q04_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_calfixvar"]) || $this->q04_calfixvar != "")
           $resac = db_query("insert into db_acount values($acount,55,8210,'".AddSlashes(pg_result($resaco,$conresaco,'q04_calfixvar'))."','$this->q04_calfixvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_diasvcto"]) || $this->q04_diasvcto != "")
           $resac = db_query("insert into db_acount values($acount,55,8211,'".AddSlashes(pg_result($resaco,$conresaco,'q04_diasvcto'))."','$this->q04_diasvcto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q04_perccorrepadrao"]) || $this->q04_perccorrepadrao != "")
           $resac = db_query("insert into db_acount values($acount,55,17404,'".AddSlashes(pg_result($resaco,$conresaco,'q04_perccorrepadrao'))."','$this->q04_perccorrepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q04_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q04_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q04_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q04_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q04_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,270,'$q04_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,55,270,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,272,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,273,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_vbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,274,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_dtbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,6538,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,8210,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_calfixvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,8211,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_diasvcto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,55,17404,'','".AddSlashes(pg_result($resaco,$iresaco,'q04_perccorrepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cissqn
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q04_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q04_anousu = $q04_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q04_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q04_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q04_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:cissqn";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q04_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cissqn ";
     $sql .= "      inner join inflan   on  inflan.i01_codigo   = cissqn.q04_inflat";
     $sql .= "      inner join proced   on  proced.v03_codigo   = cissqn.q04_proced";
     $sql .= "      inner join histcalc on  histcalc.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec   on  tabrec.k02_codigo   = proced.v03_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($q04_anousu!=null ){
         $sql2 .= " where cissqn.q04_anousu = $q04_anousu "; 
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
   function sql_query_file ( $q04_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cissqn ";
     $sql2 = "";
     if($dbwhere==""){
       if($q04_anousu!=null ){
         $sql2 .= " where cissqn.q04_anousu = $q04_anousu "; 
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