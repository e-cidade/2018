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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptuconstr
class cl_iptuconstr { 
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
   var $j39_matric = 0; 
   var $j39_idcons = 0; 
   var $j39_ano = 0; 
   var $j39_area = 0; 
   var $j39_areap = 0; 
   var $j39_dtlan_dia = null; 
   var $j39_dtlan_mes = null; 
   var $j39_dtlan_ano = null; 
   var $j39_dtlan = null; 
   var $j39_codigo = 0; 
   var $j39_numero = 0; 
   var $j39_compl = null; 
   var $j39_dtdemo_dia = null; 
   var $j39_dtdemo_mes = null; 
   var $j39_dtdemo_ano = null; 
   var $j39_dtdemo = null; 
   var $j39_codprotdemo = null; 
   var $j39_idaument = 0; 
   var $j39_idprinc = 'f'; 
   var $j39_pavim = 0; 
   var $j39_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j39_matric = int4 = Matrícula 
                 j39_idcons = int4 = Cód. Construção 
                 j39_ano = int4 = Ano da Construção 
                 j39_area = float8 = Área da Construção M2 
                 j39_areap = float8 = Área Privada da construção M2 
                 j39_dtlan = date = Data de Inclusão 
                 j39_codigo = int4 = Logradouro 
                 j39_numero = int4 = Número 
                 j39_compl = varchar(20) = Complemento 
                 j39_dtdemo = date = Data Demolição 
                 j39_codprotdemo = varchar(40) = Processo de Protocolo 
                 j39_idaument = int4 = Origem da Construção 
                 j39_idprinc = bool = Construção Principal 
                 j39_pavim = int4 = Pavimento 
                 j39_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_iptuconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuconstr"); 
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
       $this->j39_matric = ($this->j39_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_matric"]:$this->j39_matric);
       $this->j39_idcons = ($this->j39_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_idcons"]:$this->j39_idcons);
       $this->j39_ano = ($this->j39_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_ano"]:$this->j39_ano);
       $this->j39_area = ($this->j39_area == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_area"]:$this->j39_area);
       $this->j39_areap = ($this->j39_areap == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_areap"]:$this->j39_areap);
       if($this->j39_dtlan == ""){
         $this->j39_dtlan_dia = ($this->j39_dtlan_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtlan_dia"]:$this->j39_dtlan_dia);
         $this->j39_dtlan_mes = ($this->j39_dtlan_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtlan_mes"]:$this->j39_dtlan_mes);
         $this->j39_dtlan_ano = ($this->j39_dtlan_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtlan_ano"]:$this->j39_dtlan_ano);
         if($this->j39_dtlan_dia != ""){
            $this->j39_dtlan = $this->j39_dtlan_ano."-".$this->j39_dtlan_mes."-".$this->j39_dtlan_dia;
         }
       }
       $this->j39_codigo = ($this->j39_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_codigo"]:$this->j39_codigo);
       $this->j39_numero = ($this->j39_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_numero"]:$this->j39_numero);
       $this->j39_compl = ($this->j39_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_compl"]:$this->j39_compl);
       if($this->j39_dtdemo == ""){
         $this->j39_dtdemo_dia = ($this->j39_dtdemo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_dia"]:$this->j39_dtdemo_dia);
         $this->j39_dtdemo_mes = ($this->j39_dtdemo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_mes"]:$this->j39_dtdemo_mes);
         $this->j39_dtdemo_ano = ($this->j39_dtdemo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_ano"]:$this->j39_dtdemo_ano);
         if($this->j39_dtdemo_dia != ""){
            $this->j39_dtdemo = $this->j39_dtdemo_ano."-".$this->j39_dtdemo_mes."-".$this->j39_dtdemo_dia;
         }
       }
       $this->j39_codprotdemo = ($this->j39_codprotdemo == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_codprotdemo"]:$this->j39_codprotdemo);
       $this->j39_idaument = ($this->j39_idaument == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_idaument"]:$this->j39_idaument);
       $this->j39_idprinc = ($this->j39_idprinc == "f"?@$GLOBALS["HTTP_POST_VARS"]["j39_idprinc"]:$this->j39_idprinc);
       $this->j39_pavim = ($this->j39_pavim == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_pavim"]:$this->j39_pavim);
       $this->j39_obs = ($this->j39_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_obs"]:$this->j39_obs);
     }else{
       $this->j39_matric = ($this->j39_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_matric"]:$this->j39_matric);
       $this->j39_idcons = ($this->j39_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_idcons"]:$this->j39_idcons);
     }
   }
   // funcao para inclusao
   function incluir ($j39_matric,$j39_idcons){ 
      $this->atualizacampos();
     if($this->j39_ano == null ){ 
       $this->j39_ano = "0";
     }
     if($this->j39_area == null ){ 
       $this->erro_sql = " Campo Área da Construção M2 nao Informado.";
       $this->erro_campo = "j39_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_areap == null ){ 
       $this->j39_areap = "0";
     }
     if($this->j39_dtlan == null ){ 
       $this->erro_sql = " Campo Data de Inclusão nao Informado.";
       $this->erro_campo = "j39_dtlan_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_codigo == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "j39_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "j39_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_dtdemo == null ){ 
       $this->j39_dtdemo = "null";
     }
     if($this->j39_idaument == null ){ 
       $this->j39_idaument = "0";
     }
     if($this->j39_idprinc == null ){ 
       $this->erro_sql = " Campo Construção Principal nao Informado.";
       $this->erro_campo = "j39_idprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_pavim == null ){ 
       $this->erro_sql = " Campo Pavimento nao Informado.";
       $this->erro_campo = "j39_pavim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j39_matric = $j39_matric; 
       $this->j39_idcons = $j39_idcons; 
     if(($this->j39_matric == null) || ($this->j39_matric == "") ){ 
       $this->erro_sql = " Campo j39_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j39_idcons == null) || ($this->j39_idcons == "") ){ 
       $this->erro_sql = " Campo j39_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptuconstr(
                                       j39_matric 
                                      ,j39_idcons 
                                      ,j39_ano 
                                      ,j39_area 
                                      ,j39_areap 
                                      ,j39_dtlan 
                                      ,j39_codigo 
                                      ,j39_numero 
                                      ,j39_compl 
                                      ,j39_dtdemo 
                                      ,j39_codprotdemo 
                                      ,j39_idaument 
                                      ,j39_idprinc 
                                      ,j39_pavim 
                                      ,j39_obs 
                       )
                values (
                                $this->j39_matric 
                               ,$this->j39_idcons 
                               ,$this->j39_ano 
                               ,$this->j39_area 
                               ,$this->j39_areap 
                               ,".($this->j39_dtlan == "null" || $this->j39_dtlan == ""?"null":"'".$this->j39_dtlan."'")." 
                               ,$this->j39_codigo 
                               ,$this->j39_numero 
                               ,'$this->j39_compl' 
                               ,".($this->j39_dtdemo == "null" || $this->j39_dtdemo == ""?"null":"'".$this->j39_dtdemo."'")." 
                               ,'$this->j39_codprotdemo' 
                               ,$this->j39_idaument 
                               ,'$this->j39_idprinc' 
                               ,$this->j39_pavim 
                               ,'$this->j39_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j39_matric."-".$this->j39_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j39_matric."-".$this->j39_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j39_matric."-".$this->j39_idcons;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j39_matric,$this->j39_idcons));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,150,'$this->j39_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,151,'$this->j39_idcons','I')");
       $resac = db_query("insert into db_acount values($acount,30,150,'','".AddSlashes(pg_result($resaco,0,'j39_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,151,'','".AddSlashes(pg_result($resaco,0,'j39_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,152,'','".AddSlashes(pg_result($resaco,0,'j39_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,153,'','".AddSlashes(pg_result($resaco,0,'j39_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,154,'','".AddSlashes(pg_result($resaco,0,'j39_areap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,155,'','".AddSlashes(pg_result($resaco,0,'j39_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,156,'','".AddSlashes(pg_result($resaco,0,'j39_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,157,'','".AddSlashes(pg_result($resaco,0,'j39_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,158,'','".AddSlashes(pg_result($resaco,0,'j39_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,2490,'','".AddSlashes(pg_result($resaco,0,'j39_dtdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,18437,'','".AddSlashes(pg_result($resaco,0,'j39_codprotdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,2491,'','".AddSlashes(pg_result($resaco,0,'j39_idaument'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,2532,'','".AddSlashes(pg_result($resaco,0,'j39_idprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,5085,'','".AddSlashes(pg_result($resaco,0,'j39_pavim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,30,18543,'','".AddSlashes(pg_result($resaco,0,'j39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j39_matric=null,$j39_idcons=null) { 
      $this->atualizacampos();
     $sql = " update iptuconstr set ";
     $virgula = "";
     if(trim($this->j39_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_matric"])){ 
       $sql  .= $virgula." j39_matric = $this->j39_matric ";
       $virgula = ",";
       if(trim($this->j39_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j39_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_idcons"])){ 
       $sql  .= $virgula." j39_idcons = $this->j39_idcons ";
       $virgula = ",";
       if(trim($this->j39_idcons) == null ){ 
         $this->erro_sql = " Campo Cód. Construção nao Informado.";
         $this->erro_campo = "j39_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_ano"])){ 
        if(trim($this->j39_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j39_ano"])){ 
           $this->j39_ano = "0" ; 
        } 
       $sql  .= $virgula." j39_ano = $this->j39_ano ";
       $virgula = ",";
     }
     if(trim($this->j39_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_area"])){ 
       $sql  .= $virgula." j39_area = $this->j39_area ";
       $virgula = ",";
       if(trim($this->j39_area) == null ){ 
         $this->erro_sql = " Campo Área da Construção M2 nao Informado.";
         $this->erro_campo = "j39_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_areap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_areap"])){ 
        if(trim($this->j39_areap)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j39_areap"])){ 
           $this->j39_areap = "0" ; 
        } 
       $sql  .= $virgula." j39_areap = $this->j39_areap ";
       $virgula = ",";
     }
     if(trim($this->j39_dtlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_dtlan_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j39_dtlan_dia"] !="") ){ 
       $sql  .= $virgula." j39_dtlan = '$this->j39_dtlan' ";
       $virgula = ",";
       if(trim($this->j39_dtlan) == null ){ 
         $this->erro_sql = " Campo Data de Inclusão nao Informado.";
         $this->erro_campo = "j39_dtlan_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j39_dtlan_dia"])){ 
         $sql  .= $virgula." j39_dtlan = null ";
         $virgula = ",";
         if(trim($this->j39_dtlan) == null ){ 
           $this->erro_sql = " Campo Data de Inclusão nao Informado.";
           $this->erro_campo = "j39_dtlan_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j39_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_codigo"])){ 
       $sql  .= $virgula." j39_codigo = $this->j39_codigo ";
       $virgula = ",";
       if(trim($this->j39_codigo) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "j39_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_numero"])){ 
       $sql  .= $virgula." j39_numero = $this->j39_numero ";
       $virgula = ",";
       if(trim($this->j39_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "j39_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_compl"])){ 
       $sql  .= $virgula." j39_compl = '$this->j39_compl' ";
       $virgula = ",";
     }
     if(trim($this->j39_dtdemo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_dia"] !="") ){ 
       $sql  .= $virgula." j39_dtdemo = '$this->j39_dtdemo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j39_dtdemo_dia"])){ 
         $sql  .= $virgula." j39_dtdemo = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j39_codprotdemo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_codprotdemo"])){ 
       $sql  .= $virgula." j39_codprotdemo = '$this->j39_codprotdemo' ";
       $virgula = ",";
     }
     if(trim($this->j39_idaument)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_idaument"])){ 
        if(trim($this->j39_idaument)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j39_idaument"])){ 
           $this->j39_idaument = "0" ; 
        } 
       $sql  .= $virgula." j39_idaument = $this->j39_idaument ";
       $virgula = ",";
     }
     if(trim($this->j39_idprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_idprinc"])){ 
       $sql  .= $virgula." j39_idprinc = '$this->j39_idprinc' ";
       $virgula = ",";
       if(trim($this->j39_idprinc) == null ){ 
         $this->erro_sql = " Campo Construção Principal nao Informado.";
         $this->erro_campo = "j39_idprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_pavim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_pavim"])){ 
       $sql  .= $virgula." j39_pavim = $this->j39_pavim ";
       $virgula = ",";
       if(trim($this->j39_pavim) == null ){ 
         $this->erro_sql = " Campo Pavimento nao Informado.";
         $this->erro_campo = "j39_pavim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_obs"])){ 
       $sql  .= $virgula." j39_obs = '$this->j39_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j39_matric!=null){
       $sql .= " j39_matric = $this->j39_matric";
     }
     if($j39_idcons!=null){
       $sql .= " and  j39_idcons = $this->j39_idcons";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j39_matric,$this->j39_idcons));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,150,'$this->j39_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,151,'$this->j39_idcons','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_matric"]) || $this->j39_matric != "")
           $resac = db_query("insert into db_acount values($acount,30,150,'".AddSlashes(pg_result($resaco,$conresaco,'j39_matric'))."','$this->j39_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_idcons"]) || $this->j39_idcons != "")
           $resac = db_query("insert into db_acount values($acount,30,151,'".AddSlashes(pg_result($resaco,$conresaco,'j39_idcons'))."','$this->j39_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_ano"]) || $this->j39_ano != "")
           $resac = db_query("insert into db_acount values($acount,30,152,'".AddSlashes(pg_result($resaco,$conresaco,'j39_ano'))."','$this->j39_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_area"]) || $this->j39_area != "")
           $resac = db_query("insert into db_acount values($acount,30,153,'".AddSlashes(pg_result($resaco,$conresaco,'j39_area'))."','$this->j39_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_areap"]) || $this->j39_areap != "")
           $resac = db_query("insert into db_acount values($acount,30,154,'".AddSlashes(pg_result($resaco,$conresaco,'j39_areap'))."','$this->j39_areap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_dtlan"]) || $this->j39_dtlan != "")
           $resac = db_query("insert into db_acount values($acount,30,155,'".AddSlashes(pg_result($resaco,$conresaco,'j39_dtlan'))."','$this->j39_dtlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_codigo"]) || $this->j39_codigo != "")
           $resac = db_query("insert into db_acount values($acount,30,156,'".AddSlashes(pg_result($resaco,$conresaco,'j39_codigo'))."','$this->j39_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_numero"]) || $this->j39_numero != "")
           $resac = db_query("insert into db_acount values($acount,30,157,'".AddSlashes(pg_result($resaco,$conresaco,'j39_numero'))."','$this->j39_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_compl"]) || $this->j39_compl != "")
           $resac = db_query("insert into db_acount values($acount,30,158,'".AddSlashes(pg_result($resaco,$conresaco,'j39_compl'))."','$this->j39_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_dtdemo"]) || $this->j39_dtdemo != "")
           $resac = db_query("insert into db_acount values($acount,30,2490,'".AddSlashes(pg_result($resaco,$conresaco,'j39_dtdemo'))."','$this->j39_dtdemo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_codprotdemo"]) || $this->j39_codprotdemo != "")
           $resac = db_query("insert into db_acount values($acount,30,18437,'".AddSlashes(pg_result($resaco,$conresaco,'j39_codprotdemo'))."','$this->j39_codprotdemo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_idaument"]) || $this->j39_idaument != "")
           $resac = db_query("insert into db_acount values($acount,30,2491,'".AddSlashes(pg_result($resaco,$conresaco,'j39_idaument'))."','$this->j39_idaument',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_idprinc"]) || $this->j39_idprinc != "")
           $resac = db_query("insert into db_acount values($acount,30,2532,'".AddSlashes(pg_result($resaco,$conresaco,'j39_idprinc'))."','$this->j39_idprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_pavim"]) || $this->j39_pavim != "")
           $resac = db_query("insert into db_acount values($acount,30,5085,'".AddSlashes(pg_result($resaco,$conresaco,'j39_pavim'))."','$this->j39_pavim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j39_obs"]) || $this->j39_obs != "")
           $resac = db_query("insert into db_acount values($acount,30,18543,'".AddSlashes(pg_result($resaco,$conresaco,'j39_obs'))."','$this->j39_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j39_matric."-".$this->j39_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j39_matric."-".$this->j39_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j39_matric."-".$this->j39_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j39_matric=null,$j39_idcons=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j39_matric,$j39_idcons));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,150,'$j39_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,151,'$j39_idcons','E')");
         $resac = db_query("insert into db_acount values($acount,30,150,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,151,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,152,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,153,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,154,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_areap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,155,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,156,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,157,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,158,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,2490,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_dtdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,18437,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_codprotdemo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,2491,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_idaument'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,2532,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_idprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,5085,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_pavim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,30,18543,'','".AddSlashes(pg_result($resaco,$iresaco,'j39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptuconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j39_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j39_matric = $j39_matric ";
        }
        if($j39_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j39_idcons = $j39_idcons ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j39_matric."-".$j39_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j39_matric."-".$j39_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j39_matric."-".$j39_idcons;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptuconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j39_matric=null,$j39_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstr ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      left join ruastipo on j88_codigo = j14_tipo";     
     $sql2 = "";
     if($dbwhere==""){
       if($j39_matric!=null ){
         $sql2 .= " where iptuconstr.j39_matric = $j39_matric "; 
       } 
       if($j39_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstr.j39_idcons = $j39_idcons "; 
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
   function sql_query_file ( $j39_matric=null,$j39_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($j39_matric!=null ){
         $sql2 .= " where iptuconstr.j39_matric = $j39_matric "; 
       } 
       if($j39_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstr.j39_idcons = $j39_idcons "; 
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
   function excluirGeral ($matric=null,$idcons=null,$cascade=true) { 
    
		$clcarconstr        = new cl_carconstr;
		$clconstrcar        = new cl_constrcar;
		$cliptucale         = new cl_iptucale;
		$clconstrescr       = new cl_constrescr;
		$clissmatric        = new cl_issmatric;
		$cliptuconstrdemo   = new cl_iptuconstrdemo;
		$cliptuconstrpontos = new cl_iptuconstrpontos;

    if($cascade){
			$clcarconstr->j48_matric = $matric;
			$clcarconstr->j48_idcons = $idcons;
			$clcarconstr->excluir($matric,$idcons,null);
			if ($clcarconstr->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Carconstr - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $clcarconstr->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
			$clconstrcar->j53_matric = $matric;
			$clconstrcar->j53_idcons = $idcons;
			$clconstrcar->excluir($matric,$idcons,null);
			if ($clconstrcar->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Constrcar - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $clconstrcar->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
			$cliptucale->j22_matric = $matric;
			$cliptucale->j22_idcons = $idcons;
			$cliptucale->excluir(null,$matric,$idcons);
			if ($cliptucale->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Iptucale - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $cliptucale->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
			$clconstrescr->j52_matric = $matric;
			$clconstrescr->j52_idcons = $idcons;
			$clconstrescr->excluir($matric,$idcons);
			if ($clconstrescr->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Constrescr - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $clconstrescr->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}

			$clissmatric->excluir(null,null," q05_matric = $matric and q05_idcons = $idcons ");
			if ($clissmatric->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Issmatric - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $clissmatric->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
			$cliptuconstrdemo->excluir($matric,$idcons,null);
			if ($cliptuconstrdemo->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Iptuconstrdemo - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $cliptuconstrdemo->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
			$cliptuconstrpontos->excluir(null," j83_matric = $matric and j83_idcons = $idcons ");
			if ($clcarconstr->erro_status == 0) {
				 $this->erro_banco       = str_replace("\n","",@pg_last_error());
				 $this->erro_sql         = " Iptuconstrpontos - Nao Excluído. Exclusão Abortada.\\n";
				 $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
				 $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				 $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				 $this->erro_msg        .= $cliptuconstrpontos->erro_msg;
				 $this->erro_status = "0";
				 return false;
			}
	  }

		$this->excluir($matric,$idcons);
		if ($this->erro_status == 0) {
       $this->erro_banco       = str_replace("\n","",@pg_last_error());
       $this->erro_sql         = " Iptuconstr - Nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql        .= "Valores : ".$matric."-".$idcons;
       $this->erro_msg         = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg        .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_msg        .= $this->erro_msg;
       $this->erro_status = "0";
       return false;
		}
    return true;
  }
   function sql_query_proprietario_nome ( $j39_matric=null,$j39_idcons=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .=" from iptuconstr ";
     $sql .="      inner join carconstr on j39_matric = j48_matric and j39_idcons = j48_idcons ";
     $sql .="      inner join proprietario_nome on j48_matric = j01_matric ";
     $sql .="      inner join caracter on j48_caract = j31_codigo ";
     $sql .="      inner join cargrup on j31_grupo = j32_grupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($j39_matric!=null ){
         $sql2 .= " where iptuconstr.j39_matric = $j39_matric ";
       }
       if($j39_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " iptuconstr.j39_idcons = $j39_idcons ";
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