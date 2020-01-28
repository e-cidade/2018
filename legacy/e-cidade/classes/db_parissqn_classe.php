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

//MODULO: issqn
//CLASSE DA ENTIDADE parissqn
class cl_parissqn {
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
   var $q60_receit = 0;
   var $q60_tipo = 0;
   var $q60_aliq = 0;
   var $q60_codvencvar = 0;
   var $q60_histsemmov = 0;
   var $q60_impcodativ = 'f';
   var $q60_impobsativ = 'f';
   var $q60_impdatas = 'f';
   var $q60_impobsissqn = 'f';
   var $q60_modalvara = 0;
   var $q60_integrasani = 0;
   var $q60_campoutilcalc = 0;
   var $q60_alvbaixadiv = 0;
   var $q60_notaavulsapesjur = 'f';
   var $q60_notaavulsavias = 0;
   var $q60_notaavulsavlrmin = 0;
   var $q60_notaavulsamax = 0;
   var $q60_notaavulsaultimanota = 0;
   var $q60_notaavulsadiasprazo = 0;
   var $q60_tipopermalvara = 0;
   var $q60_tiponumcertbaixa = 0;
   var $q60_templatealvara = 0;
   var $q60_dataimpmei_dia = null;
   var $q60_dataimpmei_mes = null;
   var $q60_dataimpmei_ano = null;
   var $q60_dataimpmei = null;
   var $q60_bloqemiscertbaixa = 0;
   var $q60_isstipoalvaraper = 0;
   var $q60_isstipoalvaraprov = 0;
   var $q60_parcelasalvara = null;
   var $q60_templatebaixaalvaranormal = 0;
   var $q60_templatebaixaalvaraoficial = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q60_receit = int4 = Receita
                 q60_tipo = int4 = tipo de débito
                 q60_aliq = float8 = Aliquota padrão
                 q60_codvencvar = int4 = Código do vencimento
                 q60_histsemmov = int4 = Hist.Calc.
                 q60_impcodativ = bool = Imprime Código Atividade
                 q60_impobsativ = bool = Imprime Observação Atividade
                 q60_impdatas = bool = Imprime Datas
                 q60_impobsissqn = bool = Observação do ISSQN
                 q60_modalvara = int4 = Modelo Alvará
                 q60_integrasani = int4 = Integração com Sanitario
                 q60_campoutilcalc = int4 = Variável para calculo
                 q60_alvbaixadiv = int4 = Permite baixa de alvara com dívida
                 q60_notaavulsapesjur = bool = Permite Nota Avulsa Pes. Jur
                 q60_notaavulsavias = int4 = Número de Vias da Nota
                 q60_notaavulsavlrmin = float4 = Valor Mínimo a ser pago
                 q60_notaavulsamax = int4 = Número Máximo de Notas Avulsas
                 q60_notaavulsaultimanota = int4 = Número da Última Nota Avulsa
                 q60_notaavulsadiasprazo = int4 = Prazo para Validade do Recibo (dias)
                 q60_tipopermalvara = int4 = Permissao para alterar alvará com CNPJ
                 q60_tiponumcertbaixa = int4 = Numeração a ser utilizada
                 q60_templatealvara = int4 = Documento Alvará
                 q60_dataimpmei = date = Data Implantação MEI
                 q60_bloqemiscertbaixa = int4 = Bloqueio emissão certidão de baixa
                 q60_isstipoalvaraper = int4 = Alvará Permanente
                 q60_isstipoalvaraprov = int4 = Alvará Provisório
                 q60_parcelasalvara = varchar(3) = Limite Parcelas Alvará
                 q60_templatebaixaalvaranormal = int4 = Certidão de Baixa de Alvará Normal
                 q60_templatebaixaalvaraoficial = int4 = Certidão de Baixa de Alvará Oficial
                 ";
   //funcao construtor da classe
   function cl_parissqn() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parissqn");
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
       $this->q60_receit = ($this->q60_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_receit"]:$this->q60_receit);
       $this->q60_tipo = ($this->q60_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_tipo"]:$this->q60_tipo);
       $this->q60_aliq = ($this->q60_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_aliq"]:$this->q60_aliq);
       $this->q60_codvencvar = ($this->q60_codvencvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_codvencvar"]:$this->q60_codvencvar);
       $this->q60_histsemmov = ($this->q60_histsemmov == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_histsemmov"]:$this->q60_histsemmov);
       $this->q60_impcodativ = ($this->q60_impcodativ == "f"?@$GLOBALS["HTTP_POST_VARS"]["q60_impcodativ"]:$this->q60_impcodativ);
       $this->q60_impobsativ = ($this->q60_impobsativ == "f"?@$GLOBALS["HTTP_POST_VARS"]["q60_impobsativ"]:$this->q60_impobsativ);
       $this->q60_impdatas = ($this->q60_impdatas == "f"?@$GLOBALS["HTTP_POST_VARS"]["q60_impdatas"]:$this->q60_impdatas);
       $this->q60_impobsissqn = ($this->q60_impobsissqn == "f"?@$GLOBALS["HTTP_POST_VARS"]["q60_impobsissqn"]:$this->q60_impobsissqn);
       $this->q60_modalvara = ($this->q60_modalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_modalvara"]:$this->q60_modalvara);
       $this->q60_integrasani = ($this->q60_integrasani == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_integrasani"]:$this->q60_integrasani);
       $this->q60_campoutilcalc = ($this->q60_campoutilcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_campoutilcalc"]:$this->q60_campoutilcalc);
       $this->q60_alvbaixadiv = ($this->q60_alvbaixadiv == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_alvbaixadiv"]:$this->q60_alvbaixadiv);
       $this->q60_notaavulsapesjur = ($this->q60_notaavulsapesjur == "f"?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsapesjur"]:$this->q60_notaavulsapesjur);
       $this->q60_notaavulsavias = ($this->q60_notaavulsavias == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavias"]:$this->q60_notaavulsavias);
       $this->q60_notaavulsavlrmin = ($this->q60_notaavulsavlrmin == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavlrmin"]:$this->q60_notaavulsavlrmin);
       $this->q60_notaavulsamax = ($this->q60_notaavulsamax == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsamax"]:$this->q60_notaavulsamax);
       $this->q60_notaavulsaultimanota = ($this->q60_notaavulsaultimanota == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsaultimanota"]:$this->q60_notaavulsaultimanota);
       $this->q60_notaavulsadiasprazo = ($this->q60_notaavulsadiasprazo == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_notaavulsadiasprazo"]:$this->q60_notaavulsadiasprazo);
       $this->q60_tipopermalvara = ($this->q60_tipopermalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_tipopermalvara"]:$this->q60_tipopermalvara);
       $this->q60_tiponumcertbaixa = ($this->q60_tiponumcertbaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_tiponumcertbaixa"]:$this->q60_tiponumcertbaixa);
       $this->q60_templatealvara = ($this->q60_templatealvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_templatealvara"]:$this->q60_templatealvara);
       if($this->q60_dataimpmei == ""){
         $this->q60_dataimpmei_dia = ($this->q60_dataimpmei_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_dia"]:$this->q60_dataimpmei_dia);
         $this->q60_dataimpmei_mes = ($this->q60_dataimpmei_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_mes"]:$this->q60_dataimpmei_mes);
         $this->q60_dataimpmei_ano = ($this->q60_dataimpmei_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_ano"]:$this->q60_dataimpmei_ano);
         if($this->q60_dataimpmei_dia != ""){
            $this->q60_dataimpmei = $this->q60_dataimpmei_ano."-".$this->q60_dataimpmei_mes."-".$this->q60_dataimpmei_dia;
         }
       }
       $this->q60_bloqemiscertbaixa = ($this->q60_bloqemiscertbaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_bloqemiscertbaixa"]:$this->q60_bloqemiscertbaixa);
       $this->q60_isstipoalvaraper = ($this->q60_isstipoalvaraper == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraper"]:$this->q60_isstipoalvaraper);
       $this->q60_isstipoalvaraprov = ($this->q60_isstipoalvaraprov == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraprov"]:$this->q60_isstipoalvaraprov);
       $this->q60_parcelasalvara = ($this->q60_parcelasalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_parcelasalvara"]:$this->q60_parcelasalvara);
       $this->q60_templatebaixaalvaranormal = ($this->q60_templatebaixaalvaranormal == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaranormal"]:$this->q60_templatebaixaalvaranormal);
       $this->q60_templatebaixaalvaraoficial = ($this->q60_templatebaixaalvaraoficial == ""?@$GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaraoficial"]:$this->q60_templatebaixaalvaraoficial);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->q60_receit == null ){
       $this->erro_sql = " Campo Receita não informado.";
       $this->erro_campo = "q60_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_tipo == null ){
       $this->erro_sql = " Campo tipo de débito não informado.";
       $this->erro_campo = "q60_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_aliq == null ){
       $this->erro_sql = " Campo Aliquota padrão não informado.";
       $this->erro_campo = "q60_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_codvencvar == null ){
       $this->erro_sql = " Campo Código do vencimento não informado.";
       $this->erro_campo = "q60_codvencvar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_histsemmov == null ){
       $this->erro_sql = " Campo Hist.Calc. não informado.";
       $this->erro_campo = "q60_histsemmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_impcodativ == null ){
       $this->erro_sql = " Campo Imprime Código Atividade não informado.";
       $this->erro_campo = "q60_impcodativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_impobsativ == null ){
       $this->erro_sql = " Campo Imprime Observação Atividade não informado.";
       $this->erro_campo = "q60_impobsativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_impdatas == null ){
       $this->erro_sql = " Campo Imprime Datas não informado.";
       $this->erro_campo = "q60_impdatas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_impobsissqn == null ){
       $this->erro_sql = " Campo Observação do ISSQN não informado.";
       $this->erro_campo = "q60_impobsissqn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_modalvara == null ){
       $this->erro_sql = " Campo Modelo Alvará não informado.";
       $this->erro_campo = "q60_modalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_integrasani == null ){
       $this->erro_sql = " Campo Integração com Sanitario não informado.";
       $this->erro_campo = "q60_integrasani";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_campoutilcalc == null ){
       $this->erro_sql = " Campo Variável para calculo não informado.";
       $this->erro_campo = "q60_campoutilcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_alvbaixadiv == null ){
       $this->erro_sql = " Campo Permite baixa de alvara com dívida não informado.";
       $this->erro_campo = "q60_alvbaixadiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_notaavulsapesjur == null ){
       $this->q60_notaavulsapesjur = "f";
     }
     if($this->q60_notaavulsavias == null ){
       $this->erro_sql = " Campo Número de Vias da Nota não informado.";
       $this->erro_campo = "q60_notaavulsavias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_notaavulsavlrmin == null ){
       $this->erro_sql = " Campo Valor Mínimo a ser pago não informado.";
       $this->erro_campo = "q60_notaavulsavlrmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_notaavulsamax == null ){
       $this->q60_notaavulsamax = "0";
     }
     if($this->q60_notaavulsaultimanota == null ){
       $this->erro_sql = " Campo Número da Última Nota Avulsa não informado.";
       $this->erro_campo = "q60_notaavulsaultimanota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_notaavulsadiasprazo == null ){
       $this->q60_notaavulsadiasprazo = "0";
     }
     if($this->q60_tipopermalvara == null ){
       $this->erro_sql = " Campo Permissao para alterar alvará com CNPJ não informado.";
       $this->erro_campo = "q60_tipopermalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_tiponumcertbaixa == null ){
       $this->erro_sql = " Campo Num. da Certidão Baixa não informado.";
       $this->erro_campo = "q60_tiponumcertbaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_templatealvara == null ){
       $this->q60_templatealvara = "0";
     }
     if($this->q60_dataimpmei == null ){
       $this->q60_dataimpmei = "null";
     }
     if($this->q60_bloqemiscertbaixa == null ){
       $this->erro_sql = " Campo Bloqueio emissão certidão de baixa não informado.";
       $this->erro_campo = "q60_bloqemiscertbaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_isstipoalvaraper == null ){
       $this->erro_sql = " Campo Alvará Permanente não informado.";
       $this->erro_campo = "q60_isstipoalvaraper";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_isstipoalvaraprov == null ){
       $this->erro_sql = " Campo Alvará Provisório não informado.";
       $this->erro_campo = "q60_isstipoalvaraprov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q60_parcelasalvara == null ){
       $this->q60_parcelasalvara = "1";
     }
     if($this->q60_templatebaixaalvaranormal == null ){
       $this->q60_templatebaixaalvaranormal = "null";
     }
     if($this->q60_templatebaixaalvaraoficial == null ){
       $this->q60_templatebaixaalvaraoficial = "null";
     }
     $sql = "insert into parissqn(
                                       q60_receit
                                      ,q60_tipo
                                      ,q60_aliq
                                      ,q60_codvencvar
                                      ,q60_histsemmov
                                      ,q60_impcodativ
                                      ,q60_impobsativ
                                      ,q60_impdatas
                                      ,q60_impobsissqn
                                      ,q60_modalvara
                                      ,q60_integrasani
                                      ,q60_campoutilcalc
                                      ,q60_alvbaixadiv
                                      ,q60_notaavulsapesjur
                                      ,q60_notaavulsavias
                                      ,q60_notaavulsavlrmin
                                      ,q60_notaavulsamax
                                      ,q60_notaavulsaultimanota
                                      ,q60_notaavulsadiasprazo
                                      ,q60_tipopermalvara
                                      ,q60_tiponumcertbaixa
                                      ,q60_templatealvara
                                      ,q60_dataimpmei
                                      ,q60_bloqemiscertbaixa
                                      ,q60_isstipoalvaraper
                                      ,q60_isstipoalvaraprov
                                      ,q60_parcelasalvara
                                      ,q60_templatebaixaalvaranormal
                                      ,q60_templatebaixaalvaraoficial
                       )
                values (
                                $this->q60_receit
                               ,$this->q60_tipo
                               ,$this->q60_aliq
                               ,$this->q60_codvencvar
                               ,$this->q60_histsemmov
                               ,'$this->q60_impcodativ'
                               ,'$this->q60_impobsativ'
                               ,'$this->q60_impdatas'
                               ,'$this->q60_impobsissqn'
                               ,$this->q60_modalvara
                               ,$this->q60_integrasani
                               ,$this->q60_campoutilcalc
                               ,$this->q60_alvbaixadiv
                               ,'$this->q60_notaavulsapesjur'
                               ,$this->q60_notaavulsavias
                               ,$this->q60_notaavulsavlrmin
                               ,$this->q60_notaavulsamax
                               ,$this->q60_notaavulsaultimanota
                               ,$this->q60_notaavulsadiasprazo
                               ,$this->q60_tipopermalvara
                               ,$this->q60_tiponumcertbaixa
                               ,$this->q60_templatealvara
                               ,".($this->q60_dataimpmei == "null" || $this->q60_dataimpmei == ""?"null":"'".$this->q60_dataimpmei."'")."
                               ,$this->q60_bloqemiscertbaixa
                               ,$this->q60_isstipoalvaraper
                               ,$this->q60_isstipoalvaraprov
                               ,'$this->q60_parcelasalvara'
                               ,$this->q60_templatebaixaalvaranormal
                               ,$this->q60_templatebaixaalvaraoficial
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores padrões do ISSQN () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores padrões do ISSQN já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores padrões do ISSQN () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   }
   // funcao para alteracao
   function alterar ( $oid=null ) {
      $this->atualizacampos();
     $sql = " update parissqn set ";
     $virgula = "";
     if(trim($this->q60_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_receit"])){
       $sql  .= $virgula." q60_receit = $this->q60_receit ";
       $virgula = ",";
       if(trim($this->q60_receit) == null ){
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "q60_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tipo"])){
       $sql  .= $virgula." q60_tipo = $this->q60_tipo ";
       $virgula = ",";
       if(trim($this->q60_tipo) == null ){
         $this->erro_sql = " Campo tipo de débito não informado.";
         $this->erro_campo = "q60_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_aliq"])){
       $sql  .= $virgula." q60_aliq = $this->q60_aliq ";
       $virgula = ",";
       if(trim($this->q60_aliq) == null ){
         $this->erro_sql = " Campo Aliquota padrão não informado.";
         $this->erro_campo = "q60_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_codvencvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_codvencvar"])){
       $sql  .= $virgula." q60_codvencvar = $this->q60_codvencvar ";
       $virgula = ",";
       if(trim($this->q60_codvencvar) == null ){
         $this->erro_sql = " Campo Código do vencimento não informado.";
         $this->erro_campo = "q60_codvencvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_histsemmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_histsemmov"])){
       $sql  .= $virgula." q60_histsemmov = $this->q60_histsemmov ";
       $virgula = ",";
       if(trim($this->q60_histsemmov) == null ){
         $this->erro_sql = " Campo Hist.Calc. não informado.";
         $this->erro_campo = "q60_histsemmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impcodativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impcodativ"])){
       $sql  .= $virgula." q60_impcodativ = '$this->q60_impcodativ' ";
       $virgula = ",";
       if(trim($this->q60_impcodativ) == null ){
         $this->erro_sql = " Campo Imprime Código Atividade não informado.";
         $this->erro_campo = "q60_impcodativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impobsativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impobsativ"])){
       $sql  .= $virgula." q60_impobsativ = '$this->q60_impobsativ' ";
       $virgula = ",";
       if(trim($this->q60_impobsativ) == null ){
         $this->erro_sql = " Campo Imprime Observação Atividade não informado.";
         $this->erro_campo = "q60_impobsativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impdatas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impdatas"])){
       $sql  .= $virgula." q60_impdatas = '$this->q60_impdatas' ";
       $virgula = ",";
       if(trim($this->q60_impdatas) == null ){
         $this->erro_sql = " Campo Imprime Datas não informado.";
         $this->erro_campo = "q60_impdatas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impobsissqn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impobsissqn"])){
       $sql  .= $virgula." q60_impobsissqn = '$this->q60_impobsissqn' ";
       $virgula = ",";
       if(trim($this->q60_impobsissqn) == null ){
         $this->erro_sql = " Campo Observação do ISSQN não informado.";
         $this->erro_campo = "q60_impobsissqn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_modalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_modalvara"])){
       $sql  .= $virgula." q60_modalvara = $this->q60_modalvara ";
       $virgula = ",";
       if(trim($this->q60_modalvara) == null ){
         $this->erro_sql = " Campo Modelo Alvará não informado.";
         $this->erro_campo = "q60_modalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_integrasani)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_integrasani"])){
       $sql  .= $virgula." q60_integrasani = $this->q60_integrasani ";
       $virgula = ",";
       if(trim($this->q60_integrasani) == null ){
         $this->erro_sql = " Campo Integração com Sanitario não informado.";
         $this->erro_campo = "q60_integrasani";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_campoutilcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_campoutilcalc"])){
       $sql  .= $virgula." q60_campoutilcalc = $this->q60_campoutilcalc ";
       $virgula = ",";
       if(trim($this->q60_campoutilcalc) == null ){
         $this->erro_sql = " Campo Variável para calculo não informado.";
         $this->erro_campo = "q60_campoutilcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_alvbaixadiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_alvbaixadiv"])){
       $sql  .= $virgula." q60_alvbaixadiv = $this->q60_alvbaixadiv ";
       $virgula = ",";
       if(trim($this->q60_alvbaixadiv) == null ){
         $this->erro_sql = " Campo Permite baixa de alvara com dívida não informado.";
         $this->erro_campo = "q60_alvbaixadiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsapesjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsapesjur"])){
       $sql  .= $virgula." q60_notaavulsapesjur = '$this->q60_notaavulsapesjur' ";
       $virgula = ",";
     }
     if(trim($this->q60_notaavulsavias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavias"])){
       $sql  .= $virgula." q60_notaavulsavias = $this->q60_notaavulsavias ";
       $virgula = ",";
       if(trim($this->q60_notaavulsavias) == null ){
         $this->erro_sql = " Campo Número de Vias da Nota não informado.";
         $this->erro_campo = "q60_notaavulsavias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsavlrmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavlrmin"])){
       $sql  .= $virgula." q60_notaavulsavlrmin = $this->q60_notaavulsavlrmin ";
       $virgula = ",";
       if(trim($this->q60_notaavulsavlrmin) == null ){
         $this->erro_sql = " Campo Valor Mínimo a ser pago não informado.";
         $this->erro_campo = "q60_notaavulsavlrmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsamax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsamax"])){
        if(trim($this->q60_notaavulsamax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsamax"])){
           $this->q60_notaavulsamax = "0" ;
        }
       $sql  .= $virgula." q60_notaavulsamax = $this->q60_notaavulsamax ";
       $virgula = ",";
     }
     if(trim($this->q60_notaavulsaultimanota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsaultimanota"])){
       $sql  .= $virgula." q60_notaavulsaultimanota = $this->q60_notaavulsaultimanota ";
       $virgula = ",";
       if(trim($this->q60_notaavulsaultimanota) == null ){
         $this->erro_sql = " Campo Número da Última Nota Avulsa não informado.";
         $this->erro_campo = "q60_notaavulsaultimanota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsadiasprazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsadiasprazo"])){
        if(trim($this->q60_notaavulsadiasprazo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsadiasprazo"])){
           $this->q60_notaavulsadiasprazo = "0" ;
        }
       $sql  .= $virgula." q60_notaavulsadiasprazo = $this->q60_notaavulsadiasprazo ";
       $virgula = ",";
     }
     if(trim($this->q60_tipopermalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tipopermalvara"])){
       $sql  .= $virgula." q60_tipopermalvara = $this->q60_tipopermalvara ";
       $virgula = ",";
       if(trim($this->q60_tipopermalvara) == null ){
         $this->erro_sql = " Campo Permissao para alterar alvará com CNPJ não informado.";
         $this->erro_campo = "q60_tipopermalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_tiponumcertbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tiponumcertbaixa"])){
       $sql  .= $virgula." q60_tiponumcertbaixa = $this->q60_tiponumcertbaixa ";
       $virgula = ",";
       if(trim($this->q60_tiponumcertbaixa) == null ){
         $this->erro_sql = " Campo Num. da Certidão Baixa não informado.";
         $this->erro_campo = "q60_tiponumcertbaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_templatealvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_templatealvara"])){
        if(trim($this->q60_templatealvara)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_templatealvara"])){
           $this->q60_templatealvara = "0" ;
        }
       $sql  .= $virgula." q60_templatealvara = $this->q60_templatealvara ";
       $virgula = ",";
     }
     if(trim($this->q60_dataimpmei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_dia"] !="") ){
       $sql  .= $virgula." q60_dataimpmei = '$this->q60_dataimpmei' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei_dia"])){
         $sql  .= $virgula." q60_dataimpmei = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q60_bloqemiscertbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_bloqemiscertbaixa"])){
       $sql  .= $virgula." q60_bloqemiscertbaixa = $this->q60_bloqemiscertbaixa ";
       $virgula = ",";
       if(trim($this->q60_bloqemiscertbaixa) == null ){
         $this->erro_sql = " Campo Bloqueio emissão certidão de baixa não informado.";
         $this->erro_campo = "q60_bloqemiscertbaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_isstipoalvaraper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraper"])){
       $sql  .= $virgula." q60_isstipoalvaraper = $this->q60_isstipoalvaraper ";
       $virgula = ",";
       if(trim($this->q60_isstipoalvaraper) == null ){
         $this->erro_sql = " Campo Alvará Permanente não informado.";
         $this->erro_campo = "q60_isstipoalvaraper";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_isstipoalvaraprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraprov"])){
       $sql  .= $virgula." q60_isstipoalvaraprov = $this->q60_isstipoalvaraprov ";
       $virgula = ",";
       if(trim($this->q60_isstipoalvaraprov) == null ){
         $this->erro_sql = " Campo Alvará Provisório não informado.";
         $this->erro_campo = "q60_isstipoalvaraprov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_parcelasalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_parcelasalvara"])){
       $sql  .= $virgula." q60_parcelasalvara = '$this->q60_parcelasalvara' ";
       $virgula = ",";
     }
     if(trim($this->q60_templatebaixaalvaranormal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaranormal"])){
        if(trim($this->q60_templatebaixaalvaranormal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaranormal"])){
           $this->q60_templatebaixaalvaranormal = "null" ;
        }
       $sql  .= $virgula." q60_templatebaixaalvaranormal = $this->q60_templatebaixaalvaranormal ";
       $virgula = ",";
     }
     if(trim($this->q60_templatebaixaalvaraoficial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaraoficial"])){
        if(trim($this->q60_templatebaixaalvaraoficial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaraoficial"])){
           $this->q60_templatebaixaalvaraoficial = "null" ;
        }
       $sql  .= $virgula." q60_templatebaixaalvaraoficial = $this->q60_templatebaixaalvaraoficial ";
       $virgula = ",";
     }
     $sql .= " where ";
     $sql .= "oid = '$oid'";

     $result = db_query($sql);

     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores padrões do ISSQN nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores padrões do ISSQN nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $oid=null ,$dbwhere=null) {

     $sql = " delete from parissqn
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores padrões do ISSQN nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores padrões do ISSQN nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:parissqn";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $oid = null,$campos="parissqn.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from parissqn ";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = parissqn.q60_codvencvar";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = parissqn.q60_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = parissqn.q60_tipo";
     $sql .= "       left  join db_documentotemplate  on  db_documentotemplate.db82_sequencial = parissqn.q60_templatealvara";
     $sql .= "       left join isstipoalvara  on  isstipoalvara.q98_sequencial = parissqn.q60_isstipoalvaraper";
     $sql .= "       left join isstipoalvara t on  t.q98_sequencial = parissqn.q60_isstipoalvaraper";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = cadvencdesc.q92_hist";
     $sql .= "      inner join arretipo  as a on   a.k00_tipo = cadvencdesc.q92_tipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "       left join db_config  as b on   b.codigo = db_documentotemplate.db82_instit";
     $sql .= "       left join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where parissqn.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from parissqn ";
     $sql2 = "";
     if($dbwhere==""){
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
   function alterarParametro() {

     $this->atualizacampos();
     $sql = " update parissqn set ";
     $virgula = "";
     if(trim($this->q60_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_receit"])){
       $sql  .= $virgula." q60_receit = $this->q60_receit ";
       $virgula = ",";
       if(trim($this->q60_receit) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "q60_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tipo"])){
       $sql  .= $virgula." q60_tipo = $this->q60_tipo ";
       $virgula = ",";
       if(trim($this->q60_tipo) == null ){
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "q60_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_aliq"])){
       $sql  .= $virgula." q60_aliq = $this->q60_aliq ";
       $virgula = ",";
       if(trim($this->q60_aliq) == null ){
         $this->erro_sql = " Campo Aliquota padrão nao Informado.";
         $this->erro_campo = "q60_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_codvencvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_codvencvar"])){
       $sql  .= $virgula." q60_codvencvar = $this->q60_codvencvar ";
       $virgula = ",";
       if(trim($this->q60_codvencvar) == null ){
         $this->erro_sql = " Campo codigo do vencimento nao Informado.";
         $this->erro_campo = "q60_codvencvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_histsemmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_histsemmov"])){
       $sql  .= $virgula." q60_histsemmov = $this->q60_histsemmov ";
       $virgula = ",";
       if(trim($this->q60_histsemmov) == null ){
         $this->erro_sql = " Campo Hist.Calc. nao Informado.";
         $this->erro_campo = "q60_histsemmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impcodativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impcodativ"])){
       $sql  .= $virgula." q60_impcodativ = '$this->q60_impcodativ' ";
       $virgula = ",";
       if(trim($this->q60_impcodativ) == null ){
         $this->erro_sql = " Campo Imprime Código Atividade nao Informado.";
         $this->erro_campo = "q60_impcodativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impobsativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impobsativ"])){
       $sql  .= $virgula." q60_impobsativ = '$this->q60_impobsativ' ";
       $virgula = ",";
       if(trim($this->q60_impobsativ) == null ){
         $this->erro_sql = " Campo Imprime Observação Atividade nao Informado.";
         $this->erro_campo = "q60_impobsativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impdatas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impdatas"])){
       $sql  .= $virgula." q60_impdatas = '$this->q60_impdatas' ";
       $virgula = ",";
       if(trim($this->q60_impdatas) == null ){
         $this->erro_sql = " Campo Imprime Datas nao Informado.";
         $this->erro_campo = "q60_impdatas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_impobsissqn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_impobsissqn"])){
       $sql  .= $virgula." q60_impobsissqn = '$this->q60_impobsissqn' ";
       $virgula = ",";
       if(trim($this->q60_impobsissqn) == null ){
         $this->erro_sql = " Campo Observação do ISSQN nao Informado.";
         $this->erro_campo = "q60_impobsissqn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_modalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_modalvara"])){
       $sql  .= $virgula." q60_modalvara = $this->q60_modalvara ";
       $virgula = ",";
       if(trim($this->q60_modalvara) == null ){
         $this->erro_sql = " Campo Modelo Alvara nao Informado.";
         $this->erro_campo = "q60_modalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->q60_templatealvara != "") {
     	$sql  .= $virgula." q60_templatealvara = $this->q60_templatealvara ";
      $virgula = ",";
     }
     if(trim($this->q60_integrasani)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_integrasani"])){
       $sql  .= $virgula." q60_integrasani = $this->q60_integrasani ";
       $virgula = ",";
       if(trim($this->q60_integrasani) == null ){
         $this->erro_sql = " Campo Integração com Sanitario nao Informado.";
         $this->erro_campo = "q60_integrasani";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_campoutilcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_campoutilcalc"])){
       $sql  .= $virgula." q60_campoutilcalc = $this->q60_campoutilcalc ";
       $virgula = ",";
       if(trim($this->q60_campoutilcalc) == null ){
         $this->erro_sql = " Campo Variável para calculo nao Informado.";
         $this->erro_campo = "q60_campoutilcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_alvbaixadiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_alvbaixadiv"])){
       $sql  .= $virgula." q60_alvbaixadiv = $this->q60_alvbaixadiv ";
       $virgula = ",";
       if(trim($this->q60_alvbaixadiv) == null ){
         $this->erro_sql = " Campo Permite baixa de alvara com dívida nao Informado.";
         $this->erro_campo = "q60_alvbaixadiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsapesjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsapesjur"])){
       $sql  .= $virgula." q60_notaavulsapesjur = '$this->q60_notaavulsapesjur' ";
       $virgula = ",";
     }
     if(trim($this->q60_notaavulsavias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavias"])){
       $sql  .= $virgula." q60_notaavulsavias = $this->q60_notaavulsavias ";
       $virgula = ",";
       if(trim($this->q60_notaavulsavias) == null ){
         $this->erro_sql = " Campo Número de Vias da Nota nao Informado.";
         $this->erro_campo = "q60_notaavulsavias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsavlrmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsavlrmin"])){
       $sql  .= $virgula." q60_notaavulsavlrmin = $this->q60_notaavulsavlrmin ";
       $virgula = ",";
       if(trim($this->q60_notaavulsavlrmin) == null ){
         $this->erro_sql = " Campo Valor Minimo a ser pago nao Informado.";
         $this->erro_campo = "q60_notaavulsavlrmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsamax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsamax"])){
        if(trim($this->q60_notaavulsamax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsamax"])){
           $this->q60_notaavulsamax = "0" ;
        }
       $sql  .= $virgula." q60_notaavulsamax = $this->q60_notaavulsamax ";
       $virgula = ",";
     }
     if(trim($this->q60_notaavulsaultimanota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsaultimanota"])){
       $sql  .= $virgula." q60_notaavulsaultimanota = $this->q60_notaavulsaultimanota ";
       $virgula = ",";
       if(trim($this->q60_notaavulsaultimanota) == null ){
         $this->erro_sql = " Campo Número da Última Nota Avulsa nao Informado.";
         $this->erro_campo = "q60_notaavulsaultimanota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_notaavulsadiasprazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsadiasprazo"])){
        if(trim($this->q60_notaavulsadiasprazo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_notaavulsadiasprazo"])){
           $this->q60_notaavulsadiasprazo = "0" ;
        }
       $sql  .= $virgula." q60_notaavulsadiasprazo = $this->q60_notaavulsadiasprazo ";
       $virgula = ",";
     }
     if(trim($this->q60_tipopermalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tipopermalvara"])){
       $sql  .= $virgula." q60_tipopermalvara = $this->q60_tipopermalvara ";
       $virgula = ",";
       if(trim($this->q60_tipopermalvara) == null ){
         $this->erro_sql = " Campo Permissao para alterar alvara com CNPJ nao Informado.";
         $this->erro_campo = "q60_tipopermalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_tiponumcertbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_tiponumcertbaixa"])){
       $sql  .= $virgula." q60_tiponumcertbaixa = $this->q60_tiponumcertbaixa ";
       $virgula = ",";
       if(trim($this->q60_tiponumcertbaixa) == null ){
         $this->erro_sql = " Campo Numeração a ser utilizada nao Informado.";
         $this->erro_campo = "q60_tiponumcertbaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_isstipoalvaraper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraper"])){
       $sql  .= $virgula." q60_isstipoalvaraper = $this->q60_isstipoalvaraper ";
       $virgula = ",";
       if(trim($this->q60_tiponumcertbaixa) == null ){
         $this->erro_sql = " Campo Numeração a ser utilizada não Informado.";
         $this->erro_campo = "q60_isstipoalvaraper";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q60_isstipoalvaraprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_isstipoalvaraprov"])){
       $sql  .= $virgula." q60_isstipoalvaraprov = $this->q60_isstipoalvaraprov ";
       $virgula = ",";
       if(trim($this->q60_tiponumcertbaixa) == null ){
         $this->erro_sql = " Campo Numeração a ser utilizada não Informado.";
         $this->erro_campo = "q60_isstipoalvaraprov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (strtolower(trim($this->q60_dataimpmei))!="null" && (trim($this->q60_dataimpmei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_dataimpmei"]))){

       $data = $dData = implode("-", array_reverse(explode("/",$this->q60_dataimpmei)));
       $sql  .= $virgula." q60_dataimpmei =  '$dData'";
       $virgula = ",";

     } else if (strtolower(trim($this->q60_dataimpmei))=="null"){
       $sql .= $virgula." q60_dataimpmei = null";
     }

     if(trim($this->q60_bloqemiscertbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_bloqemiscertbaixa"])){
       $sql  .= $virgula." q60_bloqemiscertbaixa = $this->q60_bloqemiscertbaixa ";
       $virgula = ",";
       if(trim($this->q60_bloqemiscertbaixa) == null ){
         $this->erro_sql = " Campo Bloqueio emissão certidão de baixa nao Informado.";
         $this->erro_campo = "q60_bloqemiscertbaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->q60_parcelasalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_parcelasalvara"])){
       $sql  .= $virgula." q60_parcelasalvara = '$this->q60_parcelasalvara' ";
       $virgula = ",";
     }

     if(trim($this->q60_templatebaixaalvaranormal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaranormal"])){
        if(trim($this->q60_templatebaixaalvaranormal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaranormal"])){
           $this->q60_templatebaixaalvaranormal = "null" ;
        }
       $sql  .= $virgula." q60_templatebaixaalvaranormal = $this->q60_templatebaixaalvaranormal ";
       $virgula = ",";
     }
     if(trim($this->q60_templatebaixaalvaraoficial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaraoficial"])){
        if(trim($this->q60_templatebaixaalvaraoficial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q60_templatebaixaalvaraoficial"])){
           $this->q60_templatebaixaalvaraoficial = "null" ;
        }
       $sql  .= $virgula." q60_templatebaixaalvaraoficial = $this->q60_templatebaixaalvaraoficial ";
       $virgula = ",";
     }

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores padrões do ISSQN nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores padrões do ISSQN nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   /**
   * Retorna objeto com as configurções do módulo issqn
   * @return mixed boolean|object
   */
   function getParametrosIssqn(){

    $sSql  = $this->sql_query_file();
    $rsSql = db_query($sSql);

    if (!$rsSql) {

    	$this->erro_status = "0";
    	$this->erro_msg    = "Erro ao Buscar Parametros do ISSQN.";

    	return false;
    }

    $oParametrosISSQN = db_utils::fieldsMemory($rsSql,0);
    return $oParametrosISSQN;
   }
}