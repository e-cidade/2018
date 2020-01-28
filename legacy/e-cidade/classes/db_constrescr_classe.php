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

//MODULO: cadastro
//CLASSE DA ENTIDADE constrescr
class cl_constrescr { 
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
   var $j52_matric = 0; 
   var $j52_idcons = 0; 
   var $j52_ano = 0; 
   var $j52_area = 0; 
   var $j52_areap = 0; 
   var $j52_dtlan_dia = null; 
   var $j52_dtlan_mes = null; 
   var $j52_dtlan_ano = null; 
   var $j52_dtlan = null; 
   var $j52_codigo = 0; 
   var $j52_numero = 0; 
   var $j52_compl = null; 
   var $j52_dtdemo_dia = null; 
   var $j52_dtdemo_mes = null; 
   var $j52_dtdemo_ano = null; 
   var $j52_dtdemo = null; 
   var $j52_idaument = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j52_matric = int4 = Matricula 
                 j52_idcons = int4 = Codigo Construcao 
                 j52_ano = int4 = Ano Construcao 
                 j52_area = float8 = Area Construida 
                 j52_areap = float8 = Area Privada 
                 j52_dtlan = date = Data Inclusao 
                 j52_codigo = int4 = Rua 
                 j52_numero = int4 = Numero 
                 j52_compl = varchar(20) = Complemento 
                 j52_dtdemo = date = Data Demolição 
                 j52_idaument = int4 = Construção Principal 
                 ";
   //funcao construtor da classe 
   function cl_constrescr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("constrescr"); 
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
       $this->j52_matric = ($this->j52_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_matric"]:$this->j52_matric);
       $this->j52_idcons = ($this->j52_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_idcons"]:$this->j52_idcons);
       $this->j52_ano = ($this->j52_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_ano"]:$this->j52_ano);
       $this->j52_area = ($this->j52_area == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_area"]:$this->j52_area);
       $this->j52_areap = ($this->j52_areap == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_areap"]:$this->j52_areap);
       if($this->j52_dtlan == ""){
         $this->j52_dtlan_dia = ($this->j52_dtlan_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtlan_dia"]:$this->j52_dtlan_dia);
         $this->j52_dtlan_mes = ($this->j52_dtlan_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtlan_mes"]:$this->j52_dtlan_mes);
         $this->j52_dtlan_ano = ($this->j52_dtlan_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtlan_ano"]:$this->j52_dtlan_ano);
         if($this->j52_dtlan_dia != ""){
            $this->j52_dtlan = $this->j52_dtlan_ano."-".$this->j52_dtlan_mes."-".$this->j52_dtlan_dia;
         }
       }
       $this->j52_codigo = ($this->j52_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_codigo"]:$this->j52_codigo);
       $this->j52_numero = ($this->j52_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_numero"]:$this->j52_numero);
       $this->j52_compl = ($this->j52_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_compl"]:$this->j52_compl);
       if($this->j52_dtdemo == ""){
         $this->j52_dtdemo_dia = ($this->j52_dtdemo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_dia"]:$this->j52_dtdemo_dia);
         $this->j52_dtdemo_mes = ($this->j52_dtdemo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_mes"]:$this->j52_dtdemo_mes);
         $this->j52_dtdemo_ano = ($this->j52_dtdemo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_ano"]:$this->j52_dtdemo_ano);
         if($this->j52_dtdemo_dia != ""){
            $this->j52_dtdemo = $this->j52_dtdemo_ano."-".$this->j52_dtdemo_mes."-".$this->j52_dtdemo_dia;
         }
       }
       $this->j52_idaument = ($this->j52_idaument == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_idaument"]:$this->j52_idaument);
     }else{
       $this->j52_matric = ($this->j52_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_matric"]:$this->j52_matric);
       $this->j52_idcons = ($this->j52_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j52_idcons"]:$this->j52_idcons);
     }
   }
   // funcao para inclusao
   function incluir ($j52_matric,$j52_idcons){ 
      $this->atualizacampos();
     if($this->j52_ano == null ){ 
       $this->erro_sql = " Campo Ano Construcao nao Informado.";
       $this->erro_campo = "j52_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j52_area == null ){ 
       $this->erro_sql = " Campo Area Construida nao Informado.";
       $this->erro_campo = "j52_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j52_areap == null ){ 
       $this->j52_areap = "0";
     }
     if($this->j52_dtlan == null ){ 
       $this->erro_sql = " Campo Data Inclusao nao Informado.";
       $this->erro_campo = "j52_dtlan_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j52_codigo == null ){ 
       $this->erro_sql = " Campo Rua nao Informado.";
       $this->erro_campo = "j52_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j52_numero == null ){ 
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "j52_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j52_dtdemo == null ){ 
       $this->j52_dtdemo = "null";
     }
     if($this->j52_idaument == null ){ 
       $this->j52_idaument = "0";
     }
       $this->j52_matric = $j52_matric; 
       $this->j52_idcons = $j52_idcons; 
     if(($this->j52_matric == null) || ($this->j52_matric == "") ){ 
       $this->erro_sql = " Campo j52_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j52_idcons == null) || ($this->j52_idcons == "") ){ 
       $this->erro_sql = " Campo j52_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into constrescr(
                                       j52_matric 
                                      ,j52_idcons 
                                      ,j52_ano 
                                      ,j52_area 
                                      ,j52_areap 
                                      ,j52_dtlan 
                                      ,j52_codigo 
                                      ,j52_numero 
                                      ,j52_compl 
                                      ,j52_dtdemo 
                                      ,j52_idaument 
                       )
                values (
                                $this->j52_matric 
                               ,$this->j52_idcons 
                               ,$this->j52_ano 
                               ,$this->j52_area 
                               ,$this->j52_areap 
                               ,".($this->j52_dtlan == "null" || $this->j52_dtlan == ""?"null":"'".$this->j52_dtlan."'")." 
                               ,$this->j52_codigo 
                               ,$this->j52_numero 
                               ,'$this->j52_compl' 
                               ,".($this->j52_dtdemo == "null" || $this->j52_dtdemo == ""?"null":"'".$this->j52_dtdemo."'")." 
                               ,$this->j52_idaument 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j52_matric."-".$this->j52_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j52_matric."-".$this->j52_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j52_matric."-".$this->j52_idcons;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j52_matric,$this->j52_idcons));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,172,'$this->j52_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,173,'$this->j52_idcons','I')");
       $resac = db_query("insert into db_acount values($acount,35,172,'','".AddSlashes(pg_result($resaco,0,'j52_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,173,'','".AddSlashes(pg_result($resaco,0,'j52_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,174,'','".AddSlashes(pg_result($resaco,0,'j52_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,175,'','".AddSlashes(pg_result($resaco,0,'j52_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,176,'','".AddSlashes(pg_result($resaco,0,'j52_areap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,177,'','".AddSlashes(pg_result($resaco,0,'j52_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,178,'','".AddSlashes(pg_result($resaco,0,'j52_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,179,'','".AddSlashes(pg_result($resaco,0,'j52_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,180,'','".AddSlashes(pg_result($resaco,0,'j52_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,2492,'','".AddSlashes(pg_result($resaco,0,'j52_dtdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,35,2493,'','".AddSlashes(pg_result($resaco,0,'j52_idaument'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j52_matric=null,$j52_idcons=null) { 
      $this->atualizacampos();
     $sql = " update constrescr set ";
     $virgula = "";
     if(trim($this->j52_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_matric"])){ 
       $sql  .= $virgula." j52_matric = $this->j52_matric ";
       $virgula = ",";
       if(trim($this->j52_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j52_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_idcons"])){ 
       $sql  .= $virgula." j52_idcons = $this->j52_idcons ";
       $virgula = ",";
       if(trim($this->j52_idcons) == null ){ 
         $this->erro_sql = " Campo Codigo Construcao nao Informado.";
         $this->erro_campo = "j52_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_ano"])){ 
       $sql  .= $virgula." j52_ano = $this->j52_ano ";
       $virgula = ",";
       if(trim($this->j52_ano) == null ){ 
         $this->erro_sql = " Campo Ano Construcao nao Informado.";
         $this->erro_campo = "j52_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_area"])){ 
       $sql  .= $virgula." j52_area = $this->j52_area ";
       $virgula = ",";
       if(trim($this->j52_area) == null ){ 
         $this->erro_sql = " Campo Area Construida nao Informado.";
         $this->erro_campo = "j52_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_areap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_areap"])){ 
        if(trim($this->j52_areap)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j52_areap"])){ 
           $this->j52_areap = "0" ; 
        } 
       $sql  .= $virgula." j52_areap = $this->j52_areap ";
       $virgula = ",";
     }
     if(trim($this->j52_dtlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_dtlan_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j52_dtlan_dia"] !="") ){ 
       $sql  .= $virgula." j52_dtlan = '$this->j52_dtlan' ";
       $virgula = ",";
       if(trim($this->j52_dtlan) == null ){ 
         $this->erro_sql = " Campo Data Inclusao nao Informado.";
         $this->erro_campo = "j52_dtlan_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j52_dtlan_dia"])){ 
         $sql  .= $virgula." j52_dtlan = null ";
         $virgula = ",";
         if(trim($this->j52_dtlan) == null ){ 
           $this->erro_sql = " Campo Data Inclusao nao Informado.";
           $this->erro_campo = "j52_dtlan_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j52_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_codigo"])){ 
       $sql  .= $virgula." j52_codigo = $this->j52_codigo ";
       $virgula = ",";
       if(trim($this->j52_codigo) == null ){ 
         $this->erro_sql = " Campo Rua nao Informado.";
         $this->erro_campo = "j52_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_numero"])){ 
       $sql  .= $virgula." j52_numero = $this->j52_numero ";
       $virgula = ",";
       if(trim($this->j52_numero) == null ){ 
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "j52_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j52_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_compl"])){ 
       $sql  .= $virgula." j52_compl = '$this->j52_compl' ";
       $virgula = ",";
     }
     if(trim($this->j52_dtdemo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_dia"] !="") ){ 
       $sql  .= $virgula." j52_dtdemo = '$this->j52_dtdemo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j52_dtdemo_dia"])){ 
         $sql  .= $virgula." j52_dtdemo = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j52_idaument)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j52_idaument"])){ 
        if(trim($this->j52_idaument)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j52_idaument"])){ 
           $this->j52_idaument = "0" ; 
        } 
       $sql  .= $virgula." j52_idaument = $this->j52_idaument ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j52_matric!=null){
       $sql .= " j52_matric = $this->j52_matric";
     }
     if($j52_idcons!=null){
       $sql .= " and  j52_idcons = $this->j52_idcons";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j52_matric,$this->j52_idcons));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,172,'$this->j52_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,173,'$this->j52_idcons','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_matric"]))
           $resac = db_query("insert into db_acount values($acount,35,172,'".AddSlashes(pg_result($resaco,$conresaco,'j52_matric'))."','$this->j52_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_idcons"]))
           $resac = db_query("insert into db_acount values($acount,35,173,'".AddSlashes(pg_result($resaco,$conresaco,'j52_idcons'))."','$this->j52_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_ano"]))
           $resac = db_query("insert into db_acount values($acount,35,174,'".AddSlashes(pg_result($resaco,$conresaco,'j52_ano'))."','$this->j52_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_area"]))
           $resac = db_query("insert into db_acount values($acount,35,175,'".AddSlashes(pg_result($resaco,$conresaco,'j52_area'))."','$this->j52_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_areap"]))
           $resac = db_query("insert into db_acount values($acount,35,176,'".AddSlashes(pg_result($resaco,$conresaco,'j52_areap'))."','$this->j52_areap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_dtlan"]))
           $resac = db_query("insert into db_acount values($acount,35,177,'".AddSlashes(pg_result($resaco,$conresaco,'j52_dtlan'))."','$this->j52_dtlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_codigo"]))
           $resac = db_query("insert into db_acount values($acount,35,178,'".AddSlashes(pg_result($resaco,$conresaco,'j52_codigo'))."','$this->j52_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_numero"]))
           $resac = db_query("insert into db_acount values($acount,35,179,'".AddSlashes(pg_result($resaco,$conresaco,'j52_numero'))."','$this->j52_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_compl"]))
           $resac = db_query("insert into db_acount values($acount,35,180,'".AddSlashes(pg_result($resaco,$conresaco,'j52_compl'))."','$this->j52_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_dtdemo"]))
           $resac = db_query("insert into db_acount values($acount,35,2492,'".AddSlashes(pg_result($resaco,$conresaco,'j52_dtdemo'))."','$this->j52_dtdemo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j52_idaument"]))
           $resac = db_query("insert into db_acount values($acount,35,2493,'".AddSlashes(pg_result($resaco,$conresaco,'j52_idaument'))."','$this->j52_idaument',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j52_matric."-".$this->j52_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j52_matric."-".$this->j52_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j52_matric."-".$this->j52_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j52_matric=null,$j52_idcons=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j52_matric,$j52_idcons));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,172,'$j52_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,173,'$j52_idcons','E')");
         $resac = db_query("insert into db_acount values($acount,35,172,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,173,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,174,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,175,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,176,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_areap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,177,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,178,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,179,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,180,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,2492,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_dtdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,35,2493,'','".AddSlashes(pg_result($resaco,$iresaco,'j52_idaument'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from constrescr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j52_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j52_matric = $j52_matric ";
        }
        if($j52_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j52_idcons = $j52_idcons ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j52_matric."-".$j52_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j52_matric."-".$j52_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j52_matric."-".$j52_idcons;
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
        $this->erro_sql   = "Record Vazio na Tabela:constrescr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j52_matric=null,$j52_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from constrescr ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = constrescr.j52_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = constrescr.j52_matric";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j52_matric!=null ){
         $sql2 .= " where constrescr.j52_matric = $j52_matric "; 
       } 
       if($j52_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrescr.j52_idcons = $j52_idcons "; 
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
   function sql_query_file ( $j52_matric=null,$j52_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from constrescr ";
     $sql2 = "";
     if($dbwhere==""){
       if($j52_matric!=null ){
         $sql2 .= " where constrescr.j52_matric = $j52_matric "; 
       } 
       if($j52_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " constrescr.j52_idcons = $j52_idcons "; 
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